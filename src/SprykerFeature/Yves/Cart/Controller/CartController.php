<?php

namespace SprykerFeature\Yves\Cart\Controller;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Yves\Application\Business\Application;
use SprykerEngine\Yves\Application\Communication\Controller\AbstractController;
use SprykerEngine\Yves\Kernel\Factory;
use SprykerFeature\Sdk\Cart\Model\CartInterface;
use SprykerFeature\Yves\Cart\Exception\MissingCartException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\InvalidParameterException;

class CartController extends AbstractController
{
    /**
     * @var CartInterface
     */
    private $cart;

    /**
     * @param Application $app
     * @param Factory $factory
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(Application $app, Factory $factory, LocatorLocatorInterface $locator)
    {
        parent::__construct($app, $factory, $locator);
        $this->initCart($app);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function addItemAction(Request $request)
    {
        $sku = $this->getSkuFromRequest($request);
        $quantity = $this->getQuantityFromRequest($request);

        $cart = $this->getCart()->addToCart($sku, $quantity);

        return [
            'cart' => $cart
        ];
    }

    /**
     * @param Application $app
     *
     * @throws MissingCartException
     */
    private function initCart(Application $app)
    {
        if (!$app->offsetExists('cart') || (!($cart = $app->get('cart')) instanceof CartInterface)) {
            throw new MissingCartException('No cart provider was registered to the application');
        }

        $this->cart = $app->offsetGet('cart');
    }

    /**
     * @return CartInterface
     */
    protected function getCart()
    {
        return $this->cart;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    private function getSkuFromRequest(Request $request)
    {
        if (!$request->attributes->has('sku')) {
            throw new InvalidParameterException('sku is missing');
        }

        $sku = $request->attributes->get('sku');
        return $sku;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    private function getQuantityFromRequest(Request $request)
    {
        $quantity = $request->attributes->get('quantity', 1);
        return $quantity;
    }
}
 