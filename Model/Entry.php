<?php

namespace Bundle\AWeekOfSymfonyBundle\Model;

/**
 * Entry
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 */
class Entry
{
    protected $uri;
    protected $title;
    protected $summary;
    protected $mailingList;
    protected $highlights;
    protected $otherChangesUri;

    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
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

    public function setHighlights($name, $highlights)
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
