<?php

namespace Bundle\AWeekOfSymfonyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FieldGroup;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;
use Symfony\Component\OutputEscaper\SafeDecorator;

use Bundle\AWeekOfSymfonyBundle\Model\Entry;
use Bundle\AWeekOfSymfonyBundle\Scraper\RecentlyEntriesScraper;
use Bundle\AWeekOfSymfonyBundle\Scraper\EntryScraper;
use Bundle\AWeekOfSymfonyBundle\Renderer\MarkdownRenderer;

class EntryController extends Controller
{
    public function indexAction()
    {
        $scraper = new RecentlyEntriesScraper();
        $links = $scraper->scrape();

        $entries = array();
        foreach ($links as $link) {
            $r = explode('/', str_replace('http://www.symfony-project.org/blog/', '', $link->getUri()), 4);

            $entries[] = array(
                'uri'     => $link->getUri(),
                'content' => $link->getNode()->nodeValue,
                'route'   => array(
                    'year'  => $r[0],
                    'month' => $r[1],
                    'day'   => $r[2],
                    'slug'  => $r[3],
                ),
            );
        }

        return $this->render('AWeekOfSymfonyBundle:Entry:index', array(
            'entries' => $entries,
        ));
    }

    public function showAction($year, $month, $day, $slug)
    {
        $uri = sprintf('http://www.symfony-project.org/blog/%s/%s/%s/%s', $year, $month, $day, $slug);

        $scraper = new EntryScraper();
        $entry = $scraper->scrape($uri);

        $renderer = new MarkdownRenderer($entry);
        $markdown = $renderer->output();

        if ($this->container->get('request')->getRequestFormat() === 'markdown') {
            $response = $this->createResponse($markdown, 200, array(
                'Content-Type' => 'text/plain',
            ));
        } else {
            $response = $this->render('AWeekOfSymfonyBundle:Entry:show', array(
                'entry'    => $entry,
                'form'     => new SafeDecorator($this->createFormFromEntry($entry)),
                'markdown' => $markdown,
            ));
        }

        return $response;
    }

    private function createFormFromEntry(Entry $entry)
    {
        $form = new Form('entry', $entry, $this->container->get('validator'));

        $form->add(new TextareaField('summary'));

        $highlightGroup = new FieldGroup('allHighlights');
        foreach ($entry->getAllHighlights() as $name => $highlights) {
            $name = preg_replace('/\W/', '', $name);
            $group = new FieldGroup($name);
            foreach ($highlights as $commit => $highlight) {
                $group->add(new TextField($commit));
            }

            $highlightGroup->add($group);
        }
        $form->add($highlightGroup);

        $form->add(new TextareaField('translator', array('property_path' => null)));

        return $form;
    }
}
