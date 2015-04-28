<?php

namespace SprykerFeature\Yves\Cart;

use Silex\Application;
use SprykerEngine\Yves\Application\Communication\Plugin\YvesControllerProvider;

class CartControllerProvider extends YvesControllerProvider
{
    const ROUTE_CART_ADD = 'cart/add';

    /**
     * @param Application $app
     */
    protected function defineControllers(Application $app)
    {
        $this->createController(
            '/cart/add',
            self::ROUTE_CART_ADD,
            'Cart',
            'Cart',
            'AddItem',
            true
        );
    }

}
 