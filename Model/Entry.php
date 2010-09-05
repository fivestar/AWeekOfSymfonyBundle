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
    protected $translatorComment;

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function hasMailingList()
    {
        return isset($this->mailingList) && count($this->mailingList);
    }

    public function setMailingList($list)
    {
        $this->mailingList = $list;
    }

    public function getMailingList()
    {
        return $this->mailingList;
    }

    public function hasHighlights()
    {
        return isset($this->highlights) && count($this->highlights);
    }

    public function getAllHighlights()
    {
        return $this->highlights;
    }

    public function setHighlights($name, HighlightCollection $highlights)
    {
        $this->highlights[$name] = $highlights;
    }

    public function getHighlights($name)
    {
        return $this->highlights[$name];
    }

    public function hasOtherChanges()
    {
        return isset($this->otherChangesUri);
    }

    public function setOtherChangesUri($uri)
    {
        $this->otherChangesUri = $uri;
    }

    public function getOtherChangesUri()
    {
        return $this->otherChangesUri;
    }

    public function hasTranslatorComment()
    {
        return isset($this->translatorComment);
    }

    public function setTranslatorComment($comment) 
    {
        $this->translatorComment = $comment;
    }

    public function getTranslatorComment()
    {
        return $this->translatorComment;
    }
}
