<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface {

    private $defaultLocale;

    public function __construct() {
        //$this->defaultLocale = $defaultLocale;
        //$this->defaultLocale = $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $request = $event->getRequest();
        
        if (!$request->hasPreviousSession()) {
            return;
        }
        
//        $lang = $request->getSession()->get('lang');
//        if ($lang == null){
//            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
//            $request->getSession()->set('lang', $lang);
//        }
//
////        echo $lang; die;
//        $request->setLocale($lang);

        // try to see if the locale has been set as a _locale routing parameter
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            // if no explicit locale has been set on this request, use one from the session
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    public static function getSubscribedEvents() {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }

}
