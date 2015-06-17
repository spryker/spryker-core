<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Cart\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerFeature\Client\Cart\Model\CartInterface;

class CartServiceProvider implements ServiceProviderInterface
{
    /**
     * @var CartInterface
     */
    private $cartClient;

    /**
     * @param CartInterface $cartClient
     */
    public function __construct(CartInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['cart'] = $app->share(function () {
            return $this->cartClient;
        });
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}
