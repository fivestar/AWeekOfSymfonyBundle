<?php

namespace Bundle\AWeekOfSymfonyBundle\Model;

/**
 * Mail thread
 *
 * @author Katsuhiro Ogawa <ko.fivestar@gmail.com>
 */
class MailThread
{
    protected $uri;
    protected $subject;

    public function __construct($uri, $subject)
    {
        $this->uri = $uri;
        $this->subject = $subject;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }
}
