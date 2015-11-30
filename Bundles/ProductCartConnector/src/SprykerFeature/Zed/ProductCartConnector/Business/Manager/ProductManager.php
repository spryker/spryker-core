<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCartConnector\Business\Manager;

use Generated\Shared\Transfer\ChangeTransfer;
use SprykerFeature\Zed\Product\Business\ProductFacade;

class ProductManager implements ProductManagerInterface
{

    /**
     * @var ProductFacade
     */
    private $productFacade;

    /**
     * @param ProductFacade $productFacade
     */
    public function __construct(ProductFacade $productFacade)
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
                ->setIdAbstractProduct($concreteProductTransfer->getIdAbstractProduct())
                ->setAbstractSku($concreteProductTransfer->getAbstractProductSku())
                ->setName($concreteProductTransfer->getName());

            $taxSetTransfer = $concreteProductTransfer->getTaxSet();

            if ($taxSetTransfer !== null) {
                $cartItem->setTaxSet($taxSetTransfer);
            }
        }

        return $change;
    }

}
