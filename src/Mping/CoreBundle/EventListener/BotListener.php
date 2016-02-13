<?php

namespace Mping\CoreBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpFoundation\Response;

class BotListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }

        $userAgent = $event->getRequest()->headers->get('User-Agent');

        if (preg_match('/(googlebot|mediapartners)/i', $userAgent)) {
            $event->setResponse(new Response('', 503));
        }
    }
}