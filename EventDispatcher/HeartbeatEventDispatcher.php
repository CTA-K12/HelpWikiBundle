<?php
namespace Mesd\HelpWikiBundle\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

use Mesd\HelpWikiBundle\Event\HeartbeatEvent;

class HeartbeatEventDispatcher
{
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function onHeartbeatReceivedAction($data)
    {
        $event = new HeartbeatEvent($data);
        $this->dispatcher->dispatch('mesd_help_wiki.heartbeat_received', $event);

        return $event->getData();
    }

    public function onHeartbeatSendAction()
    {
        $event = new HeartbeatEvent();
        $this->dispatcher->dispatch('mesd_help_wiki.heartbeat_send', $event);
    }

    public function onHeartbeatTickAction()
    {
        $event = new HeartbeatEvent();
        $this->dispatcher->dispatch('mesd_help_wiki.heartbeat_tick', $event);
    }
}