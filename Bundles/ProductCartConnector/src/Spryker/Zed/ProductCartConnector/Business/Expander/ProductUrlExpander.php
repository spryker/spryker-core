<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductUrlFilterTransfer;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToLocaleInterface;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface;

class ProductUrlExpander implements ProductUrlExpanderInterface
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
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemTransfersWithUrl(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $productAbstractIds = $this->getProductAbstractIds($cartChangeTransfer);

        if (!$productAbstractIds) {
            return $cartChangeTransfer;
        }

        $urlTransfers = $this->productFacade->getProductsUrls($this->createUrlFilterTransfer($productAbstractIds));

        $mappedUrlTransfers = $this->getMappedProductUrls($urlTransfers);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $idProductAbstract = $itemTransfer->getIdProductAbstract();

            if (isset($mappedUrlTransfers[$idProductAbstract])) {
                $itemTransfer->setUrl(
                    $mappedUrlTransfers[$idProductAbstract]->getUrl()
                );
            }
        }

        return $cartChangeTransfer;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductUrlFilterTransfer
     */
    protected function createUrlFilterTransfer(array $productAbstractIds): ProductUrlFilterTransfer
    {
        return (new ProductUrlFilterTransfer())
            ->setIdLocale($this->localeFacade->getCurrentLocale()->getIdLocale())
            ->setProductAbstractIds($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return int[]
     */
    protected function getProductAbstractIds(CartChangeTransfer $cartChangeTransfer): array
    {
        $productAbstractIds = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $productAbstractIds[] = $itemTransfer->getIdProductAbstract();
        }

        return $productAbstractIds;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer[] $urlTransfers
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[] $mappedUrls
     */
    protected function getMappedProductUrls(array $urlTransfers): array
    {
        $mappedUrls = [];

        foreach ($urlTransfers as $urlTransfer) {
            $mappedUrls[$urlTransfer->getFkResourceProductAbstract()] = $urlTransfer;
        }

        return $mappedUrls;
    }
}
