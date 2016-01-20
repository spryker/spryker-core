<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface;

class ProductManager implements ProductManagerInterface
{

    /**
     * @var \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface
     */
    private $productFacade;

    /**
     * @param \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface $productFacade
     */
    public function __construct(ProductCartConnectorToProductInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $change)
    {
        foreach ($change->getItems() as $cartItem) {
            $productConcreteTransfer = $this->productFacade->getProductConcrete($cartItem->getSku());

            $cartItem->setId($productConcreteTransfer->getIdProductConcrete())
                ->setIdProductAbstract($productConcreteTransfer->getIdProductAbstract())
                ->setAbstractSku($productConcreteTransfer->getProductAbstractSku())
                ->setName($productConcreteTransfer->getName());

            if ($productConcreteTransfer->getTaxRate() !== null) {
                $cartItem->setTaxRate($productConcreteTransfer->getTaxRate());
            }
        }

        return $change;
    }

}
