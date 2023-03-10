<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        $payload = $event->getData();
        $payload['ip'] = $request->getClientIp();

        $event->setData($payload);
    }
}
