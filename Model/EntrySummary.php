<?php

namespace Bundle\AWeekOfSymfonyBundle\Model;

/**
 * EntrySummary
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 */
class EntrySummary
{
    protected $path;
    protected $uri;
    protected $subject;

    public function __construct($uri)
    {
        $this->uri = $uri;
        $this->path = str_replace('http://www.symfony-project.org/blog/', '', $uri);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setSubject($subject)
    {
        $this->title = $subject;
    }

    public function getSubject()
    {
        return $this->title;
    }
}
