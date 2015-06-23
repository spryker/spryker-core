<?php

namespace SprykerFeature\Yves\Cart\Communication\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Application\Communication\Plugin\YvesControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class CartControllerProvider extends YvesControllerProvider
{

    const ROUTE_CART = 'cart';
    const ROUTE_CART_ADD = 'cart/add';
    const ROUTE_CART_ADD_POST = 'POST_cart/add';
    const ROUTE_CART_REMOVE = 'cart/remove';
    const ROUTE_CART_CHANGE = 'cart/change';
    const ROUTE_CART_CHANGE_QUANTITY = 'cart/change/quantity';
    const ROUTE_CART_COUPON_ADD = 'cart/coupon/add';
    const ROUTE_CART_COUPON_REMOVE = 'cart/coupon/remove';
    const ROUTE_CART_COUPON_CLEAR = 'cart/coupon/clear';

    protected function defineControllers(Application $app)
    {
        $this->createGetController('/cart', self::ROUTE_CART, 'Cart', 'Cart');

        $this->createGetController('/cart/add/{sku}', self::ROUTE_CART_ADD, 'Cart', 'Cart', 'add')
            ->assert('sku', '[a-zA-Z0-9-_]+')
            ->convert('quantity', [$this, 'getQuantityFromRequest'])
        ;

        $this->createPostController('/cart/add/{sku}', self::ROUTE_CART_ADD_POST, 'Cart', 'Ajax', 'add', true)
            ->assert('sku', '[a-zA-Z0-9-_]+')
            ->convert('quantity', [$this, 'getQuantityFromRequest'])
        ;

        $this->createGetController('/cart/remove/{sku}', self::ROUTE_CART_REMOVE, 'Cart', 'Cart', 'remove')
            ->assert('sku', '[a-zA-Z0-9-_]+')
        ;

        $this->createGetController('/cart/quantity/{sku}/{absolute}', self::ROUTE_CART_CHANGE_QUANTITY, 'Cart', 'Cart', 'change')
            ->assert('sku', '[a-zA-Z0-9-_]+')
            ->assert('absolute', '[0-1-_]+')
        ;

        $this->createGetController('/cart/coupon/add', self::ROUTE_CART_COUPON_ADD, 'Cart', 'Coupon', 'add')
            ->convert(
                'couponCode',
                function ($unused, Request $request) {
                    return $request->query->get('code');
                }
            )
        ;

        $this->createGetController('/cart/coupon/remove/{couponCode}', self::ROUTE_CART_COUPON_REMOVE, 'Cart', 'Coupon', 'remove' );

        $this->createGetController('/cart/coupon/clear', self::ROUTE_CART_COUPON_CLEAR, 'Cart', 'Coupon', 'clear');
    }

    /**
     * @param mixed $unusedParameter
     * @param Request $request
     *
     * @return int
     */
    public function getQuantityFromRequest($unusedParameter, Request $request)
    {
        return (int) $request->query->get('quantity', 1);
    }

}
