<?php

namespace Bundle\AWeekOfSymfonyBundle\Scraper;

use Bundle\AWeekOfSymfonyBundle\Model\Entry;
use Bundle\AWeekOfSymfonyBundle\Model\Highlight;
use Goutte\Client;

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

        // title
        $title = $crawler->filter('#topbar h2')->text();
        $entry->setTitle($title);

        // summary
        $node = $crawler->filter('#content1 div.post p');
        $entry->setSummary($this->getHtml($this->getNode($node, 0)));

        $flag = false;
        foreach ($crawler->filter('#content1 div.post')->children() as $node) {
            if ($flag !== false) {
                switch($flag) {
                    // Development mailing list
                    case 'mailinglist':
                        $ml = array();
                        preg_match_all('!<a(?:[^>]*)href="([^"]+)"(?:[^>]*)>([^<]+)</a>!', $this->getHtml($node), $matches, PREG_SET_ORDER);
                        foreach ($matches as $m) {
                            $ml[$m[1]] = $m[2];
                        }

                        $entry->setMailingList($ml);

                        $flag = false;

                        break;

                    // Development highlights
                    case 'highlights':
                        if (!isset($highlights)) {
                            $highlights = array();
                        }

                        // headers
                        if ($node->tagName === 'p') {
                            $value = trim($node->nodeValue);

                            // other changes
                            if (false !== strpos($value, '...and many other changes')) {
                                preg_match('!href="([^"]+)"!', $this->getHtml($node), $matches);
                                $entry->setOtherChangesUri($matches[1]);
                                unset($value);
                            }

                        }
                        // contents
                        elseif ($node->tagName === 'ul') {
                            $tmp = array();

                            $listAll = $this->getHtml($node);

                            preg_match_all('!<li>(.*?)</li>!', $listAll, $list);
                            foreach ($list[1] as $li) {
                                $h = new Highlight();

                                $pos = strpos($li, '</a>: ');
                                $links = substr($li, 0, $pos + 4);
                                $text = substr($li, $pos + 6); 

                                $h->setContent(trim($text));

                                preg_match_all('!<a(?:[^>]*)href="([^"]+)"(?:[^>]*)>([^<]+)</a>!', $links, $matches, PREG_SET_ORDER);
                                foreach ($matches as $anchor) {
                                    $h->addCommit($anchor[2], $anchor[1]);
                                }
                                $tmp[] = $h;
                            }

                            $entry->setHighlights($value, $tmp);

                            unset($value);
                        }

                        break;
                }

            }

            if ($node->tagName === 'p' && trim($node->nodeValue) === 'Development mailing list') {
                $flag = 'mailinglist';
            } elseif ($node->tagName === 'p' && trim($node->nodeValue) === 'Development highlights') {
                $flag = 'highlights';
            } elseif ($node->tagName === 'p' && false !== strpos(trim($node->nodeValue), 'Development digest')) {
                // ignore after contents
                break;
            }
        }

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
        $m = $r->getMethod('getNode');
        $m->setAccessible(true);
        $n = $m->invoke($node, $n);

        return $n;
    }
}
