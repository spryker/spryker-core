<?php

namespace SprykerFeature\Yves\Cart\Router;

use SprykerEngine\Yves\Application\Business\Routing\AbstractRouter;
use Silex\Application;
use SprykerFeature\Yves\Cart\Model\CartSessionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * Class CatalogDetailRouter
 * @package Pyz\Yves\Catalog\Business\Model\Router
 * @property
 */
class CartRouter extends AbstractRouter
{

    /**
     * @var CartSessionInterface
     */
    protected $cartSession;

    /**
     * @param CartSessionInterface $cartSession
     * @param Application $app
     * @param bool $sslEnabled
     */
    public function __construct(CartSessionInterface $cartSession, Application $app, $sslEnabled = false)
    {
        $this->cartSession = $cartSession;
        parent::__construct($app, $sslEnabled);
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH)
    {
        if ('/cart' === $name) {
            $sku = $parameters['sku'];
            $skuCount = $this->cartSession->getQuantityBySku($sku);
            $pathInfo = $name . '/add/' . $sku;
            if ($skuCount > 0) {
                   $pathInfo = $name . '/quantity/' . $sku . '/' . $sku . '/0';
            }

            return $this->getUrlOrPathForType($pathInfo, $referenceType);
        }

        throw new RouteNotFoundException();
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathinfo)
    {
        throw new ResourceNotFoundException();
    }
}