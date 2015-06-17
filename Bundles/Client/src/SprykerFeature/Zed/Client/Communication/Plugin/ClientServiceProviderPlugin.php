<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Client\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Application\Communication\Plugin\TransferObject\TransferServer;
use SprykerFeature\Zed\Client\Communication\ClientControllerListenerInterface;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;

class ClientServiceProviderPlugin extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @var
     */
    protected $controllerListener;

    /**
     * @param ClientControllerListenerInterface $controllerListener
     */
    public function setControllerListener(ClientControllerListenerInterface $controllerListener)
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
