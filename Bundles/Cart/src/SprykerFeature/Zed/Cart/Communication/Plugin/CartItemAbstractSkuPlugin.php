<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Communication\Plugin;

use Generated\Shared\Cart\ChangeInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Cart\CartDependencyProvider;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use SprykerFeature\Zed\Product\Business\ProductFacade;

class CartItemAbstractSkuPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    public function expandItems(ChangeInterface $change)
    {
        $facadeProduct = $this->getProductFacade();
        foreach ($change->getItems() as $cartItem) {
            $cartItem->setAbstractSku(
                $facadeProduct->getAbstractSkuFromConcreteProduct($cartItem->getSku())
            );
        }

        return $change;
    }

    /**
     * @return ProductFacade
     * @throws \ErrorException
     */
    protected function getProductFacade()
    {
        return $this->getDependencyContainer()
            ->getProvidedDependency(CartDependencyProvider::FACADE_PRODUCT)
        ;
    }

}
