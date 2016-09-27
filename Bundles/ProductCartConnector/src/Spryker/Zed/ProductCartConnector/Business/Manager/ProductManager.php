<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToLocaleInterface;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface;

class ProductManager implements ProductManagerInterface
{
    /**
     * @var \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface $productFacade
     */
    public function __construct(
        ProductCartConnectorToLocaleInterface $localeFacade,
        ProductCartConnectorToProductInterface $productFacade
    ) {
        $this->localeFacade = $localeFacade;
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
                    $this->productFacade->getLocalizedProductConcreteName(
                        $productConcreteTransfer,
                        $this->localeFacade->getCurrentLocale()
                    )
                );
        }

        return $change;
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

        return $productConcreteTransfer;
    }

}
