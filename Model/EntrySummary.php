<?php

namespace Bundle\AWeekOfSymfonyBundle\Model;

/**
 * EntrySummary
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 */
class EntrySummary
{
    protected $uri;
    protected $content;
    protected $parts;

    public function __construct($uri, $content)
    {
        $this->uri = $uri;
        $this->content = $content;

        $parts = explode('/', str_replace('http://www.symfony-project.org/blog/', '', $uri), 4);
        $this->parts = array(
            'year'  => $parts[0],
            'month' => $parts[1],
            'day'   => $parts[2],
            'slug'  => $parts[3],
        );
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getYear()
    {
        return $this->parts['year'];
    }

    public function getMonth()
    {
        return $this->parts['month'];
    }

    public function getDay()
    {
        return $this->parts['day'];
    }

    public function getSlug()
    {
        return $this->parts['slug'];
    }

    public function getParts()
    {
        return $this->parts;
    }
}
