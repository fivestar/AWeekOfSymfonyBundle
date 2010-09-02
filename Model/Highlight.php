<?php

namespace Bundle\AWeekOfSymfonyBundle\Model;

/**
 * Highlight
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 *
 * @todo more better name
 */
class Highlight
{
    protected $commits;
    protected $content;

    public function __construct()
    {
    }

    public function addCommit($title, $uri)
    {
        $this->commits[$title] = $uri;
    }

    public function getCommits()
    {
        return $this->commits;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }
}
