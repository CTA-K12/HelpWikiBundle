<?php
namespace Mesd\HelpWikiBundle\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

use Mesd\HelpWikiBundle\Event\HeartbeatEvent;
use Mesd\HelpWikiBundle\Model\Heartbeat;

class HeartbeatEventDispatcher
{
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function onHeartbeatRequest($data)
    {
        $heartbeat = new Heartbeat($data);

        $event = new HeartbeatEvent($heartbeat);
        $this->dispatcher->dispatch('mesd_help_wiki.heartbeat_request', $event);

        return $event->getHeartbeat()->normalize();
    }

    public function onHeartbeatResponse($data)
    {
        $heartbeat = new Heartbeat($data);

        $event = new HeartbeatEvent($heartbeat);
        $this->dispatcher->dispatch('mesd_help_wiki.heartbeat_response', $event);

        return $event->getHeartbeat()->normalize();
    }
}