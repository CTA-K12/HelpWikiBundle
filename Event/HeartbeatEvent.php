<?php
namespace Mesd\HelpWikiBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Mesd\HelpWikiBundle\Model\Heartbeat;

class HeartbeatEvent extends Event
{
    protected $heartbeat;

    public function __construct(Heartbeat $heartbeat)
    {
        $this->heartbeat = $heartbeat;
    }

    public function getHeartbeat()
    {
        return $this->heartbeat;
    }
}