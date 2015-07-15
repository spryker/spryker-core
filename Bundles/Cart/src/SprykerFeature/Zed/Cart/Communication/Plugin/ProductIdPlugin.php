<?php

namespace SprykerFeature\Zed\Cart\Communication\Plugin;

use Generated\Shared\Cart\ChangeInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Cart\CartDependencyProvider;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;

class ProductIdPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{

    public function expandItems(ChangeInterface $change)
    {
        $facadeProduct = $this->getDependencyContainer()->getProvidedDependency(CartDependencyProvider::FACADE_PRODUCT);
        foreach ($change->getItems() as $cartItem) {
            $cartItem->setId($facadeProduct->getAbstractProductIdByConcreteSku($cartItem->getSku()));
        }

        return $change;
    }

}
