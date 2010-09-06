<?php

namespace Bundle\AWeekOfSymfonyBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Highlight collection
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 */
class HighlightCollection extends ArrayCollection
{
    protected $label;
    protected $changeLogUri;
    protected $summaries = array();

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setChangeLogUri($uri)
    {
        $this->changeLogUri = $uri;
    }

    public function getChangeLogUri()
    {
        if (!isset($this->changeLogUri)) {
            if (preg_match('/symfony2/i', preg_replace('/\s/', '', $this->label))) {
                return 'http://github.com/symfony/symfony/commits/master';
            }
        }

        return $this->changeLogUri;
    }

    public function hasSummaries()
    {
        return count($this->summaries) > 0;
    }

    public function addSummary($summary)
    {
        $this->summaries[] = $summary;
    }

    public function setSummaries($summaries)
    {
        $this->summaries = $summaries;
    }

    public function getSummaries()
    {
        return $this->summaries;
    }
}
