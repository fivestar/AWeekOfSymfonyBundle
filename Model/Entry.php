<?php

namespace Bundle\AWeekOfSymfonyBundle\Model;

/**
 * Entry
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 */
class Entry extends EntrySummary
{
    protected $summary;
    protected $mailingList;
    protected $highlights;
    protected $otherChangesUri;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function setMailingList($list)
    {
        $this->mailingList = $list;
    }

    public function getMailingList()
    {
        return $this->mailingList;
    }

    public function setHighlights($name, HighlightCollection $highlights)
    {
        $this->highlights[$name] = $highlights;
    }

    public function getHighlights($name)
    {
        return $this->highlights[$name];
    }

    public function getAllHighlights()
    {
        return $this->highlights;
    }

    public function setOtherChangesUri($uri)
    {
        $this->otherChangesUri = $uri;
    }

    public function getOtherChangesUri()
    {
        return $this->otherChangesUri;
    }
}
