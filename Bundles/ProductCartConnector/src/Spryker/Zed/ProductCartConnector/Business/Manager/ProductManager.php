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
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change)
    {
        foreach ($change->getItems() as $cartItem) {
            $productConcreteTransfer = $this->productFacade->getProductConcrete($cartItem->getSku());

            $cartItem->setId($productConcreteTransfer->getIdProductConcrete())
                ->setIdProductAbstract($productConcreteTransfer->getIdProductAbstract())
                ->setAbstractSku($productConcreteTransfer->getProductAbstractSku())
                ->setName($productConcreteTransfer->getName());

            $taxSetTransfer = $productConcreteTransfer->getTaxSet();

            if ($taxSetTransfer !== null) {
                $cartItem->setTaxSet($taxSetTransfer);
            }
        }

        return $change;
    }

}
