<?php

namespace Bundle\AWeekOfSymfonyBundle\Scraper;

use Bundle\AWeekOfSymfonyBundle\Model\Entry;
use Bundle\AWeekOfSymfonyBundle\Model\HighlightCollection;
use Bundle\AWeekOfSymfonyBundle\Model\Highlight;
use Bundle\AWeekOfSymfonyBundle\Model\MailThread;
use Goutte\Client;

/**
 * Entry scraper (# >= 192)
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 */
class EntryScraper
{
    /**
     * @return Entry
     */
    public function scrape($uri)
    {
        $entry = new Entry($uri);

        $client = new Client();
        $crawler = $client->request('GET', $uri);

        if ($client->getResponse()->getStatus() !== 200) {
            throw new \RuntimeException(sprintf('Status code is not 200, %d returned: %s', $client->getResponse()->getStatus(), $uri));
        }

        // subject
        $subject = $crawler->filter('#topbar h2')->text();
        $entry->setSubject($subject);

        // summary
        $node = $crawler->filter('#content1 div.post p');
        $entry->setSummary($this->getHtml($this->getNode($node, 0)));

        $state = null;
        $previousState = false;
        $nodes = iterator_to_array($crawler->filter('#content1 div.post')->children());
        $i = 0;
        do  {
            $node = $nodes[$i++];

            // seek headers
            if ($node->tagName === 'h3') {
                $nodeValue = trim($node->nodeValue);
                $previousState = $state;

                // set previous highlights
                if ($previousState === 'highlights' && isset($highlights)) {
                    $key = trim(strtolower(preg_replace('/\W/', '_', $highlights->getLabel())), '_');
                    $entry->setHighlights($key, $highlights);
                }

                if (strtolower($nodeValue) === 'development mailing list') {
                    $state = 'mailinglist';

                } elseif (stripos($nodeValue, 'development highlights')) {
                    $state = 'highlights';

                    $highlights = new HighlightCollection();
                    $highlights->setLabel($nodeValue);
                } else {
                    // ignore after contents
                    $state = false;
                }

                if ($state !== false) {
                    $node = $nodes[$i++];
                }
            }

            switch($state) {

            // Development mailing list
            case 'mailinglist':
                $ml = array();
                preg_match_all('!<a[^>]*href="([^"]+)"[^>]*>([^<]+)</a>!', $this->getHtml($node), $matches, PREG_SET_ORDER);
                foreach ($matches as $m) {
                    $ml[] = new MailThread(htmlspecialchars_decode($m[1], ENT_QUOTES), $m[2]);
                }

                $entry->setMailingList($ml);

                break;

            // Development highlights
            case 'highlights':
                // changelog and summaries
                if ($node->tagName === 'p') {
                    if (false !== stripos(trim($node->nodeValue), 'changelog')) {
                        preg_match('!href="([^"]+)"!', $this->getHtml($node), $matches);
                        $highlights->setChangeLogUri(htmlspecialchars_decode($matches[1], ENT_QUOTES));
                    } else {
                        $highlights->addSummary(trim($node->nodeValue));
                    }
                }
                // contents
                elseif ($node->tagName === 'ul') {
                    // scrape lists
                    $listAll = $this->getHtml($node);
                    preg_match_all('!<li>(.*?)</li>!s', trim($listAll), $lists);

                    foreach ($lists[1] as $li) {
                        $li = trim($li);
                        $h = new Highlight();

                        // separate commit message
                        $pos = strpos($li, '</a>:');
                        $links = substr($li, 0, $pos + 4);
                        $text = substr($li, $pos + 6); 

                        $h->setContent(preg_replace('/ {2,}/', ' ', trim($text)));

                        preg_match_all('!<a[^>]*?href="([^"]+)"[^>]*>([^<]+)</a>!s', $links, $matches, PREG_SET_ORDER);
                        foreach ($matches as $anchor) {
                            $h->addCommit($anchor[2], htmlspecialchars_decode($anchor[1], ENT_QUOTES));
                        }
                        $highlights[] = $h;
                    }

                }

                break;
            }
        } while($state !== false && count($nodes) > $i);

        return $entry;
    }

    protected function getHtml(\DOMNode $node)
    {
        $d = new \DOMDocument();
        foreach ($node->childNodes as $c) {
            $d->appendChild($d->importNode($c, true));
        }

        return $d->saveHTML();
    }

    protected function getNode($node, $n = 0)
    {
        $r = new \ReflectionObject($node);

        // Why DomCrawler::getNode() is protected...
        $m = $r->getMethod('getNode');
        $m->setAccessible(true);
        $n = $m->invoke($node, $n);

        return $n;
    }
}
