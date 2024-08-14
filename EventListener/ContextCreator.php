<?php

namespace DZunke\FeatureFlagsBundle\EventListener;

use DZunke\FeatureFlagsBundle\Context;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ContextCreator
{
    /**
     * @param Context $context
     */
    public function __construct(private readonly Context $context)
    {
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $this->context->set('client_ip', $event->getRequest()->getClientIp());
        $this->context->set('hostname', $event->getRequest()->getHost());
    }
}
