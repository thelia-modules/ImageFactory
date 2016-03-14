<?php
/*************************************************************************************/
/*      This file is part of the module ImageFactory.                                */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/
namespace ImageFactory\EventListener;

use ImageFactory\Entity\FactoryEntity;
use ImageFactory\Handler\FactoryHandler;
use ImageFactory\Util\PathInfo;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelRequestListener
 * @package ImageFactory\EventListener
 * @author Gilles Bourgeat <gilles@thelia.net>
 */
class KernelRequestListener implements EventSubscriberInterface
{
    /** @var FactoryHandler */
    protected $factoryHandler;

    /**
     * KernelRequestListener constructor.
     * @param FactoryHandler $factoryHandler
     */
    public function __construct(FactoryHandler $factoryHandler)
    {
        $this->factoryHandler = $factoryHandler;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function kernelRequestResolver(GetResponseEvent $event)
    {
        $pathInfo = new PathInfo($event->getRequest()->getPathInfo());

        // test if extension is an image, this avoids initialize the factoryResolver
        if (null !== $pathInfo->getExtension()
            && !empty($pathInfo->getBasename())
            && in_array(strtolower($pathInfo->getExtension()), FactoryEntity::FILE_EXTENSION_DESTINATION)
            && null !== $response = $this->factoryHandler->getResponse($event->getRequest())
        ) {
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['kernelRequestResolver', 192]
        ];
    }
}
