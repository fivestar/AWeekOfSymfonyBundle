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
    protected $number;

    public function __construct($uri)
    {
        $this->uri = $uri;
        $this->path = str_replace('http://www.symfony-project.org/blog/', '', $uri);

        if (preg_match('!\d{4}/\d{2}/\d{2}/a-week-of-symfony-(\d+)-!', $this->path, $matches)) {
            $this->number = $matches[1];
        }
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

    public function getNumber()
    {
        return $this->number;
    }
}
