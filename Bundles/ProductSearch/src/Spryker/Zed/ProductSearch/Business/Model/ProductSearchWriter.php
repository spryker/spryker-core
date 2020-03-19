<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Model;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductSearch\Business\Marker\ProductSearchMarkerInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class ProductSearchWriter implements ProductSearchWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductSearch\Business\Marker\ProductSearchMarkerInterface
     */
    protected $productSearchMarker;

    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected $productSearchQueryContainer;

    /**
     * @param \Spryker\Zed\ProductSearch\Business\Marker\ProductSearchMarkerInterface $productSearchMarker
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQueryContainer
     */
    public function __construct(ProductSearchMarkerInterface $productSearchMarker, ProductSearchQueryContainerInterface $productSearchQueryContainer)
    {
        $this->productSearchMarker = $productSearchMarker;
        $this->productSearchQueryContainer = $productSearchQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistProductSearch(ProductConcreteTransfer $productConcreteTransfer)
    {
        $productConcreteTransfer->requireIdProductConcrete();

        return $this->getTransactionHandler()->handleTransaction(function () use ($productConcreteTransfer): ProductConcreteTransfer {
            return $this->executePersistProductSearchTransaction($productConcreteTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function executePersistProductSearchTransaction(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $this->productSearchMarker->deactivateProductSearch(
            $productConcreteTransfer->getIdProductConcrete(),
            $this->getIsSearchableLocales($productConcreteTransfer, false)
        );

        $this->productSearchMarker->activateProductSearch(
            $productConcreteTransfer->getIdProductConcrete(),
            $this->getIsSearchableLocales($productConcreteTransfer, true)
        );

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param bool $isSearchable
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getIsSearchableLocales(ProductConcreteTransfer $productConcreteTransfer, $isSearchable)
    {
        $isSearchableLocales = [];

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            if ((bool)$localizedAttributesTransfer->getIsSearchable() === $isSearchable) {
                $isSearchableLocales[] = $localizedAttributesTransfer->getLocale();
            }
        }

        return $isSearchableLocales;
    }
}
