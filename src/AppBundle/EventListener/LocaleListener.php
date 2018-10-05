<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LocaleListener implements EventSubscriberInterface {

    private $lang;

    public function __construct($defaultLocale) {
    }

    public function onKernelResponse(FilterResponseEvent $event) {
//        $response = $event->getResponse();
//        if ($this->lang) {
//            $response->headers->setCookie(new Cookie("lang", $this->lang));
//        }
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();
//        echo '<pre>';
//        print_r($request);
//        echo '</pre>';
//        die;

        if ($event->isMasterRequest() == 1) {
            if ($request->attributes->get('_locale') == '') {

                $path = $request->attributes->get('path');
                $this->lang = $string = substr($path, 1, -1);
                $request->setLocale($this->lang);
            } else {
                $this->lang = $request->attributes->get('_locale');
//                $request->setLocale($locale);
            }
            return;
        }

        if ($locale = $request->attributes->get('_locale')) {
            if ($locale == '') {
                $path = $request->attributes->get('path');
                $this->lang = $string = substr($path, 1, -1);
                $request->setLocale($this->lang);
            } else {
                $this->lang = $locale;
                $request->setLocale($locale);
            }
            return;
        } else if (!$request->cookies->get('lang')) {
            $this->lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            $request->setLocale($this->lang);
        } else {
            // if no explicit locale has been set on this request, use one from the session
            $this->lang = $request->cookies->get('lang');
            $request->setLocale($this->lang);
        }
        $response = new RedirectResponse('/' . $this->lang . '/');
        $event->setResponse($response);
    }

    public static function getSubscribedEvents() {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }

}
