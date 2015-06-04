<?php
namespace Mesd\HelpWikiBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class HeartbeatEvent extends Event
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }
}