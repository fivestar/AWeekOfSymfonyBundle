<?php

namespace Bundle\AWeekOfSymfonyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OutputEscaper\SafeDecorator;

use Bundle\AWeekOfSymfonyBundle\Model\Entry;
use Bundle\AWeekOfSymfonyBundle\Form\EntryTranslateForm;
use Bundle\AWeekOfSymfonyBundle\Scraper\RecentlyEntriesScraper;
use Bundle\AWeekOfSymfonyBundle\Scraper\EntryScraper;
use Bundle\AWeekOfSymfonyBundle\Renderer\MarkdownRenderer;

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

    public function showAction($year = null, $month = null, $day = null, $slug = null)
    {
        $request = $this->container->get('request');

        if ($request->attribute->has('entry')) {
            $entry = $request->attribute->get('entry');
        } else {
            $entry = $this->getEntry($year, $month, $day, $slug);
        }

        $renderer = new MarkdownRenderer($entry);
        $markdown = $renderer->output();

        if ($request->getRequestFormat() === 'markdown') {
            $response = $this->createResponse($markdown, 200, array(
                'Content-Type' => 'text/plain',
            ));
        } else {
            $response = $this->render('AWeekOfSymfonyBundle:Entry:show', array(
                'entry'      => $entry,
                'markdown'   => $markdown,
                'requestUri' => $this->container->get('request')->getRequestUri(),
            ));
        }

        return $response;
    }

    public function editAction($year, $month, $day, $slug)
    {
        $request = $this->container->get('request');

        $entry = $this->getEntry($year, $month, $day, $slug);

        $response = $this->render('AWeekOfSymfonyBundle:Entry:edit', array(
            'entry'      => $entry,
            'requestUri' => $this->container->get('request')->getRequestUri(),
        ));

        return $response;
    }

    public function translateAction($year, $month, $day, $slug)
    {
        $entry = $this->getEntry($year, $month, $day, $slug);

        $request = $this->container->get('request');
        $entryData = $request->request->get('entry');

        $this->forward('AWeekOfSymfonyBundle:Entry:show', array('entry' => $entry));
    }

    private function getEntry($year, $month, $day, $slug)
    {
        $uri = sprintf('http://www.symfony-project.org/blog/%s/%s/%s/%s', $year, $month, $day, $slug);

        $scraper = new EntryScraper();
        try {
            $entry = $scraper->scrape($uri);
        } catch (\RuntimeException $e) {
            throw new NotFoundHttpException('Blog not found', $e->getCode(), $e);
        }

        return $entry;
    }
}
