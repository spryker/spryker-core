<?php

namespace SprykerFeature\Yves\Cart\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerFeature\Sdk\Cart\Model\CartInterface;

class CartServiceProvider implements ServiceProviderInterface
{
    /**
     * @var CartInterface
     */
    private $cartSdk;

    /**
     * @param CartInterface $cartSdk
     */
    public function __construct(CartInterface $cartSdk)
    {
        $this->cartSdk = $cartSdk;
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['cart'] = $app->share(function () {
            return $this->cartSdk;
        });
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}
