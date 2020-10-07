<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;
use Spryker\Zed\ProductAttribute\Business\Translator\ProductManagementAttributeTranslatorInterface;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface;

class ProductManagementAttributeReader implements ProductManagementAttributeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface
     */
    protected $productAttributeRepository;

    /**
     * @var \Spryker\Zed\ProductAttribute\Business\Translator\ProductManagementAttributeTranslatorInterface
     */
    protected $productManagementAttributeTranslator;

    /**
     * @param \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeRepositoryInterface $productAttributeRepository
     * @param \Spryker\Zed\ProductAttribute\Business\Translator\ProductManagementAttributeTranslatorInterface $productManagementAttributeTranslator
     */
    public function __construct(
        ProductAttributeRepositoryInterface $productAttributeRepository,
        ProductManagementAttributeTranslatorInterface $productManagementAttributeTranslator
    ) {
        $this->productAttributeRepository = $productAttributeRepository;
        $this->productManagementAttributeTranslator = $productManagementAttributeTranslator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer
     */
    public function getProductManagementAttributes(
        ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
    ): ProductManagementAttributeCollectionTransfer {
        $productManagementAttributeCollectionTransfer = $this->productAttributeRepository
            ->getProductManagementAttributes($productManagementAttributeFilterTransfer);

        if (!$productManagementAttributeCollectionTransfer->getProductManagementAttributes()->count()) {
            return $productManagementAttributeCollectionTransfer;
        }

        $productManagementAttributeTransfers = $productManagementAttributeCollectionTransfer->getProductManagementAttributes();

        $productManagementAttributeTransfers = $this->expandProductManagementAttributesWithValues($productManagementAttributeTransfers);
        $productManagementAttributeTransfers = $this->productManagementAttributeTranslator
            ->translateProductManagementAttributes($productManagementAttributeTransfers);

        return $productManagementAttributeCollectionTransfer
            ->setProductManagementAttributes($productManagementAttributeTransfers);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $productManagementAttributeTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    protected function expandProductManagementAttributesWithValues(ArrayObject $productManagementAttributeTransfers): ArrayObject
    {
        $productManagementAttributeIds = $this->extractProductManagementAttributeIds($productManagementAttributeTransfers);
        $productManagementAttributeValueTransfers = $this->productAttributeRepository
            ->getProductManagementAttributeValues($productManagementAttributeIds);

        $indexedProductManagementAttributeValueTransfers = $this->indexProductManagementAttributeValues($productManagementAttributeValueTransfers);

        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $values = $indexedProductManagementAttributeValueTransfers[$productManagementAttributeTransfer->getIdProductManagementAttribute()] ?? [];

            $productManagementAttributeTransfer->setValues(new ArrayObject($values));
        }

        return $productManagementAttributeTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $productManagementAttributeTransfers
     *
     * @return int[]
     */
    protected function extractProductManagementAttributeIds(ArrayObject $productManagementAttributeTransfers): array
    {
        $productManagementAttributeIds = [];

        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $productManagementAttributeIds[] = $productManagementAttributeTransfer->getIdProductManagementAttribute();
        }

        return $productManagementAttributeIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[] $productManagementAttributeValueTransfers
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer[][]
     */
    protected function indexProductManagementAttributeValues(array $productManagementAttributeValueTransfers): array
    {
        $indexedProductManagementAttributeValueTransfers = [];

        foreach ($productManagementAttributeValueTransfers as $productManagementAttributeValueTransfer) {
            $indexedProductManagementAttributeValueTransfers[$productManagementAttributeValueTransfer->getFkProductManagementAttribute()][]
                = $productManagementAttributeValueTransfer;
        }

        return $indexedProductManagementAttributeValueTransfers;
    }
}
