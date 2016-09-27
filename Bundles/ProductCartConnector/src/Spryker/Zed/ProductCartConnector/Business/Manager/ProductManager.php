<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface;

class ProductManager implements ProductManagerInterface
{

    /**
     * @var \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface
     */
    protected $productFacade;

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
            $productConcreteTransfer = $this->getAndValidateProductConcrete($cartItem->getSku());

            $cartItem->setId($productConcreteTransfer->getIdProductConcrete())
                ->setSku($productConcreteTransfer->getSku())
                ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
                ->setAbstractSku($productConcreteTransfer->getAbstractSku())
                ->setName(
                    $this->getLocalizedProductName($productConcreteTransfer)
                )
                ->setTaxRate((float)$productConcreteTransfer->getTaxRate());
        }

        return $change;
    }

    /**
     * TODO: move it to Product bundle
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    protected function getLocalizedProductName(ProductConcreteTransfer $productConcreteTransfer)
    {
        $currentLocale = Store::getInstance()->getCurrentLocale();
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttribute) {
            if (strcasecmp($localizedAttribute->getLocale()->getLocaleName(), $currentLocale) === 0) {
                return $localizedAttribute->getName();
            }
        }

        return $productConcreteTransfer->getSku();
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function getAndValidateProductConcrete($sku)
    {
        $productConcreteTransfer = $this->productFacade->getProductConcrete($sku);

        $sku = $productConcreteTransfer
            ->requireSku()
            ->getSku();

        $abstractSku = $productConcreteTransfer
            ->requireAbstractSku()
            ->getAbstractSku();

        $fkProductAbstract = $productConcreteTransfer
            ->requireFkProductAbstract()
            ->getFkProductAbstract();

        $taxRate = $productConcreteTransfer
            ->requireTaxRate()
            ->getTaxRate();

        return $productConcreteTransfer;
    }

}
