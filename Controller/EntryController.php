<?php

namespace Bundle\AWeekOfSymfonyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OutputEscaper\SafeDecorator;

use Bundle\AWeekOfSymfonyBundle\Model\Entry;
use Bundle\AWeekOfSymfonyBundle\Form\EntryTranslateForm;
use Bundle\AWeekOfSymfonyBundle\Scraper\RecentlyEntriesScraper;
use Bundle\AWeekOfSymfonyBundle\Scraper\EntryScraper;
use Bundle\AWeekOfSymfonyBundle\Scraper\EntryScraperBefore192;
use Bundle\AWeekOfSymfonyBundle\Renderer\MarkdownRenderer;
use Bundle\AWeekOfSymfonyBundle\Renderer\MarkdownFormatter;

/**
 * EntryController
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 */
class EntryController extends Controller
{
    public function indexAction()
    {
        $scraper = new RecentlyEntriesScraper();
        $entries = $scraper->scrape();

        return $this->render('AWeekOfSymfonyBundle:Entry:index', array(
            'entries' => $entries,
        ));
    }

    public function showAction($path = null)
    {
        $request = $this->container->get('request');

        if ($request->attributes->has('entry')) {
            $entry = $request->attributes->get('entry');
        } else {
            $entry = $this->getEntry($path);
        }

        $renderer = new MarkdownRenderer($entry);
        $markdown = $renderer->output();

        if ($request->getRequestFormat() === 'markdown') {
            $response = $this->createResponse($markdown, 200, array(
                'Content-Type' => 'text/plain',
            ));
        } else {
            $response = $this->render('AWeekOfSymfonyBundle:Entry:show', array(
                'entry'       => $entry,
                'markdown'    => $markdown,
                'rawMarkdown' => new SafeDecorator($markdown),
            ));
        }

        return $response;
    }

    public function editAction($path)
    {
        $request = $this->container->get('request');

        $entry = $this->getEntry($path);

        $formatter = new MarkdownFormatter();
        $markdownSummary = $formatter->linkText($entry->getSummary());

        $response = $this->render('AWeekOfSymfonyBundle:Entry:edit', array(
            'entry'           => $entry,
            'markdownSummary' => $markdownSummary,
        ));

        return $response;
    }

    public function translateAction($path)
    {
        $entry = $this->getEntry($path);

        $request = $this->container->get('request');
        $data = $request->request->get('entry');

        if (isset($data['summary']) && strlen($data['summary'])) {
            $entry->setSummary(trim($data['summary']));
        }

        if (isset($data['mailing_list'])) {
            foreach ($entry->getMailingList() as $i => $thread) {
                if (isset($data['mailing_list'][$i])) {
                    $thread->setSubject(trim($data['mailing_list'][$i]));
                }
            }
        }

        if (isset($data['highlights'])) {
            foreach ($entry->getAllHighlights() as $name => $highlights) {
                foreach ($highlights as $i => $highlight) {
                    if (isset($data['highlights'][$name]['highlight'][$i])) {
                        $highlight->setContent(trim($data['highlights'][$name]['highlight'][$i]));
                    }
                }

                if ($highlights->hasSummaries()) {
                    $summaries = $highlights->getSummaries();
                    foreach ($summaries as $i => $summary) {
                        if (isset($data['highlights'][$name]['summary'][$i])) {
                            $summaries[$i] = trim($data['highlights'][$name]['summary'][$i]);
                        }
                    }
                    $highlights->setSummaries($summaries);
                }
            }
        }

        if (isset($data['translator_comment'])) {
            $entry->setTranslatorComment(trim($data['translator_comment']));
        }


        $repository = $this->container->get('awos.repository.entry_repository');
        $repository->store($entry);

        return $this->forward('AWeekOfSymfonyBundle:Entry:show', array('entry' => $entry));
    }

    public function deleteAction($path)
    {
        $repository = $this->container->get('awos.repository.entry_repository');
        $repository->remove($path);

        $dummyEntry = new Entry($path);

        return $this->redirect($this->container->get('awos.templating.helper.entry_router')->generate('awos_edit', $dummyEntry));
    }

    private function getEntry($path)
    {
        $repository = $this->container->get('awos.repository.entry_repository');
        if (!$this->container->get('request')->query->get('original')
            && (false !== ($entry = $repository->get($path)))
        ) {
            return $entry;
        }


        $uri = sprintf('http://www.symfony-project.org/blog/%s', $path);
        $publishedDate = new \DateTime(substr($path, 0, 10));
        $threshold = new \DateTime('2010-09-05');
        if (intval($publishedDate->diff($threshold)->format('%r%a')) < 1) {
            $scraper = new EntryScraper();
        } else {
            $scraper = new EntryScraperBefore192();
        }

        try {
            $entry = $scraper->scrape($uri);
        } catch (\RuntimeException $e) {
            throw new NotFoundHttpException('Blog not found', $e->getCode(), $e);
        }

        return $entry;
    }
}
