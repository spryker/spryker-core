<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCartConnector\Business\Manager;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface;

class ProductManager implements ProductManagerInterface
{

    /**
     * @var ProductCartConnectorToProductInterface
     */
    private $productFacade;

    /**
     * @param ProductCartConnectorToProductInterface $productFacade
     */
    public function __construct(ProductCartConnectorToProductInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param ChangeTransfer $change
     *
     * @return ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change)
    {
        foreach ($change->getItems() as $cartItem) {
            $concreteProductTransfer = $this->productFacade->getConcreteProduct($cartItem->getSku());

            $cartItem->setId($concreteProductTransfer->getIdConcreteProduct())
                ->setIdProductAbstract($concreteProductTransfer->getIdProductAbstract())
                ->setAbstractSku($concreteProductTransfer->getProductAbstractSku())
                ->setName($concreteProductTransfer->getName());

            $taxSetTransfer = $concreteProductTransfer->getTaxSet();

            if ($taxSetTransfer !== null) {
                $cartItem->setTaxSet($taxSetTransfer);
            }
        }

        return $change;
    }

}
