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

    public function showAction($year, $month, $day, $slug)
    {
        $uri = sprintf('http://www.symfony-project.org/blog/%s/%s/%s/%s', $year, $month, $day, $slug);

        $scraper = new EntryScraper();
        try {
            $entry = $scraper->scrape($uri);
        } catch (\RuntimeException $e) {
            throw new NotFoundHttpException('Blog not found', $e->getCode(), $e);
        }

        $renderer = new MarkdownRenderer($entry);
        $markdown = $renderer->output();

        if ($this->container->get('request')->getRequestFormat() === 'markdown') {
            $response = $this->createResponse($markdown, 200, array(
                'Content-Type' => 'text/plain',
            ));
        } else {
            $form = new EntryTranslateForm('entry', $entry, $this->container->get('validator'));

            $response = $this->render('AWeekOfSymfonyBundle:Entry:show', array(
                'entry'    => $entry,
                'form'     => new SafeDecorator($form),
                'markdown' => $markdown,
            ));
        }

        return $response;
    }
}
