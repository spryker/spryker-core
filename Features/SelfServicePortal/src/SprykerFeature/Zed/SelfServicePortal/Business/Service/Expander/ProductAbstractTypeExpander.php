<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class ProductAbstractTypeExpander implements ProductAbstractTypeExpanderInterface
{
    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_TYPES = 'product_abstract_types';

    /**
     * @var string
     */
    protected const PRODUCT_ABSTRACT_TYPE_NAME = 'name';

    /**
     * @var string
     */
    protected const SEARCH_RESULT_KEY_PRODUCT_ABSTRACT_TYPES = 'product-abstract-types';

    /**
     * @var string
     */
    protected const STRING_FACET_KEY_PRODUCT_ABSTRACT_TYPES = 'product-abstract-types';

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     */
    public function __construct(
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function expandProductAbstractWithProductAbstractTypes(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        $productAbstractTransfer->requireIdProductAbstract();

        $productAbstractTypeTransfers = $this->selfServicePortalRepository
            ->getProductAbstractTypesByIdProductAbstract($productAbstractTransfer->getIdProductAbstractOrFail());

        foreach ($productAbstractTypeTransfers as $productAbstractTypeTransfer) {
            $productAbstractTransfer->addProductAbstractType($productAbstractTypeTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransferWithProductAbstractTypes(
        ProductPageLoadTransfer $productPageLoadTransfer
    ): ProductPageLoadTransfer {
        $productAbstractIds = $productPageLoadTransfer->getProductAbstractIds();

        if (!$productAbstractIds) {
            return $productPageLoadTransfer;
        }

        $productAbstractTypeTransfers = $this->selfServicePortalRepository->getProductAbstractTypesByProductAbstractIds($productAbstractIds);
        $indexedProductAbstractTypeTransfers = $this->indexProductAbstractTypeTransfersByProductAbstractId($productAbstractTypeTransfers);

        return $this->addProductAbstractTypesToPayloadTransfers($productPageLoadTransfer, $indexedProductAbstractTypeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array<string, mixed> $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductMapWithProductAbstractTypes(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer {
        if (!isset($productData[static::PRODUCT_ABSTRACT_TYPES]) || !is_array($productData[static::PRODUCT_ABSTRACT_TYPES])) {
            return $pageMapTransfer;
        }

        $productAbstractTypes = $productData[static::PRODUCT_ABSTRACT_TYPES];
        $productAbstractTypeNames = [];

        foreach ($productAbstractTypes as $productAbstractType) {
            if (isset($productAbstractType[static::PRODUCT_ABSTRACT_TYPE_NAME])) {
                $productAbstractTypeNames[] = $productAbstractType[static::PRODUCT_ABSTRACT_TYPE_NAME];
            }
        }

        if (!$productAbstractTypeNames) {
            return $pageMapTransfer;
        }

        $pageMapBuilder->addSearchResultData($pageMapTransfer, static::SEARCH_RESULT_KEY_PRODUCT_ABSTRACT_TYPES, $productAbstractTypeNames);

        foreach ($productAbstractTypeNames as $productAbstractTypeName) {
            $pageMapBuilder->addStringFacet($pageMapTransfer, static::STRING_FACET_KEY_PRODUCT_ABSTRACT_TYPES, $productAbstractTypeName);
        }

        return $pageMapTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     * @param array<int, array<\Generated\Shared\Transfer\ProductAbstractTypeTransfer>> $indexedProductAbstractTypeTransfers
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    protected function addProductAbstractTypesToPayloadTransfers(
        ProductPageLoadTransfer $productPageLoadTransfer,
        array $indexedProductAbstractTypeTransfers
    ): ProductPageLoadTransfer {
        $updatedPayloadTransfers = [];

        foreach ($productPageLoadTransfer->getPayloadTransfers() as $payloadTransfer) {
            $idProductAbstract = $payloadTransfer->getIdProductAbstract();
            $productAbstractTypes = $indexedProductAbstractTypeTransfers[$idProductAbstract] ?? [];

            $updatedPayloadTransfers[$idProductAbstract] = $this->updatePayloadTransferWithProductAbstractTypes(
                $payloadTransfer,
                $productAbstractTypes,
            );
        }

        return $productPageLoadTransfer->setPayloadTransfers($updatedPayloadTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPayloadTransfer $productPayloadTransfer
     * @param array<\Generated\Shared\Transfer\ProductAbstractTypeTransfer> $productAbstractTypeTransfers
     *
     * @return \Generated\Shared\Transfer\ProductPayloadTransfer
     */
    protected function updatePayloadTransferWithProductAbstractTypes(
        ProductPayloadTransfer $productPayloadTransfer,
        array $productAbstractTypeTransfers
    ): ProductPayloadTransfer {
        $productPayloadTransfer->setProductAbstractTypes(new ArrayObject($productAbstractTypeTransfers));

        return $productPayloadTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractTypeTransfer> $productAbstractTypeTransfers
     *
     * @return array<int, array<\Generated\Shared\Transfer\ProductAbstractTypeTransfer>>
     */
    protected function indexProductAbstractTypeTransfersByProductAbstractId(array $productAbstractTypeTransfers): array
    {
        $indexedProductAbstractTypeTransfers = [];

        foreach ($productAbstractTypeTransfers as $productAbstractTypeTransfer) {
            $productAbstractIds = $productAbstractTypeTransfer->getFkProductAbstracts();
            foreach ($productAbstractIds as $productAbstractId) {
                $clonedProductAbstractTypeTransfer = clone $productAbstractTypeTransfer;
                $indexedProductAbstractTypeTransfers[$productAbstractId][] = $clonedProductAbstractTypeTransfer;
            }
        }

        return $indexedProductAbstractTypeTransfers;
    }
}
