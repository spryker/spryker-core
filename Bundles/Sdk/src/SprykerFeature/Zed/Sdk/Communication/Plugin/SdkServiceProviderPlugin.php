<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sdk\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Application\Communication\Plugin\TransferObject\TransferServer;
use SprykerFeature\Zed\Sdk\Communication\SdkControllerListenerInterface;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;

class SdkServiceProviderPlugin extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @var
     */
    protected $controllerListener;

    /**
     * @param SdkControllerListenerInterface $controllerListener
     */
    public function setControllerListener(SdkControllerListenerInterface $controllerListener)
    {
        $this->controllerListener = $controllerListener;
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        /* @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcher */
        $dispatcher = $app['dispatcher'];
        $dispatcher->addListener(
            KernelEvents::CONTROLLER,
            [
                $this->controllerListener,
                'onKernelController'
            ]
        );
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app->before(function (Request $request) {
            TransferServer::getInstance()->setRequest($request);
        });
    }
}
