<?php

namespace SprykerFeature\Yves\Cart\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\Cart\Router\CartRouter;

/**
 * Class CartRouterPlugin
 * @package SprykerFeature\Yves\Cart
 */
class CartRouterPlugin extends AbstractPlugin
{
    /**
     * @param Application $app
     * @param bool $sslEnabled
     * @return CartRouter
     */
    public function createCartRouter(Application $app, $sslEnabled = false)
    {
        return $this->getDependencyContainer()->createCartRouter($app, $sslEnabled);
    }
}
