<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductRelation\DataProvider;

use Generated\Shared\Transfer\StorageProductAbstractRelationTransfer;
use Generated\Shared\Transfer\StorageProductRelationsTransfer;
use Spryker\Client\ProductRelation\ProductRelationClientInterface;
use Spryker\Shared\ProductRelation\ProductRelationTypes;
use Spryker\Yves\ProductRelation\Sorting\RelationSorterInterface;

class UpSellingDataProvider implements ProductRelationDataProviderInterface
{
    public const PARAMETER_ABSTRACT_PRODUCT_IDS = 'abstractProductIds';
    public const PARAMETER_QUOTE_TRANSFER = 'quote';

    /**
     * @var \Spryker\Client\ProductRelation\ProductRelationClientInterface
     */
    protected $productRelationClient;

    /**
     * @var \Spryker\Yves\ProductRelation\Sorting\RelationSorterInterface
     */
    protected $relationSorter;

    /**
     * @param \Spryker\Client\ProductRelation\ProductRelationClientInterface $productRelationClient
     * @param \Spryker\Yves\ProductRelation\Sorting\RelationSorterInterface $relationSorter
     */
    public function __construct(
        ProductRelationClientInterface $productRelationClient,
        RelationSorterInterface $relationSorter
    ) {
        $this->productRelationClient = $productRelationClient;
        $this->relationSorter = $relationSorter;
    }

    /**
     * @param array $parameters , parameters can be (quote => QuoteTransfer, or array of abstract product ids [1,2,3])
     *
     * @return \Generated\Shared\Transfer\StorageProductAbstractRelationTransfer[]
     */
    public function buildTemplateData(array $parameters)
    {
        if (!$this->isAllRequiredParametersProvided($parameters)) {
            return [];
        }

        $productAbstractIds = $this->extractProductAbstractIds($parameters);

        $upSellingProducts = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $productRelationsCollection = $this->productRelationClient
                ->getProductRelationsByIdProductAbstract($idProductAbstract);

            $productRelationTransfer = $this->extractProductRelationTransfer($productRelationsCollection);
            if (!$this->isProductRelationActive($productRelationTransfer)) {
                continue;
            }

            $productsToAdd = (array)$productRelationTransfer->getAbstractProducts();
            if (count($upSellingProducts) > 0) {
                $productsToAdd = $this->findNotIncludedAbstractProducts($upSellingProducts, $productsToAdd);
            }

            $this->relationSorter->sort($productsToAdd);

            $upSellingProducts = array_merge($upSellingProducts, $productsToAdd);
        }

        return $upSellingProducts;
    }

    /**
     * @return string
     */
    public function getAcceptedType()
    {
        return ProductRelationTypes::TYPE_UP_SELLING;
    }

    /**
     * @param array $productRelationCollection
     *
     * @return \Generated\Shared\Transfer\StorageProductRelationsTransfer|null
     */
    protected function extractProductRelationTransfer(array $productRelationCollection)
    {
        if (!isset($productRelationCollection[$this->getAcceptedType()])) {
            return null;
        }
        return $productRelationCollection[$this->getAcceptedType()];
    }

    /**
     * @param \Generated\Shared\Transfer\StorageProductAbstractRelationTransfer[] $upSellingProducts
     * @param \Generated\Shared\Transfer\StorageProductAbstractRelationTransfer[] $compareWithProducts
     *
     * @return \Generated\Shared\Transfer\StorageProductAbstractRelationTransfer[]
     */
    protected function findNotIncludedAbstractProducts(array $upSellingProducts, array $compareWithProducts)
    {
        return array_udiff(
            $compareWithProducts,
            $upSellingProducts,
            function (StorageProductAbstractRelationTransfer $a, StorageProductAbstractRelationTransfer $b) {
                return strcasecmp($a->getSku(), $b->getSku());
            }
        );
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function extractProductAbstractIds(array $parameters)
    {
        if (isset($parameters[static::PARAMETER_ABSTRACT_PRODUCT_IDS])) {
            return $parameters[static::PARAMETER_ABSTRACT_PRODUCT_IDS];
        }

        if (isset($parameters[static::PARAMETER_QUOTE_TRANSFER])) {
            return $this->extractAbstractProductIdsFromQuote($parameters);
        }

        return [];
    }

    /**
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function extractQuoteTransfer(array $parameters)
    {
        return $parameters[static::PARAMETER_QUOTE_TRANSFER];
    }

    /**
     * @param array $parameters
     *
     * @return bool
     */
    protected function isAllRequiredParametersProvided(array $parameters)
    {
        return isset($parameters[static::PARAMETER_ABSTRACT_PRODUCT_IDS]) || isset($parameters[static::PARAMETER_QUOTE_TRANSFER]);
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function extractAbstractProductIdsFromQuote(array $parameters)
    {
        $productAbstractIds = [];
        $quoteTransfer = $this->extractQuoteTransfer($parameters);
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }
            $productAbstractIds[$itemTransfer->getIdProductAbstract()] = $itemTransfer->getIdProductAbstract();
        }

        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            $productAbstractIds[$itemTransfer->getIdProductAbstract()] = $itemTransfer->getIdProductAbstract();
        }

        return $productAbstractIds;
    }

    /**
     * @param \Generated\Shared\Transfer\StorageProductRelationsTransfer|null $productRelationTransfer
     *
     * @return bool
     */
    protected function isProductRelationActive(?StorageProductRelationsTransfer $productRelationTransfer = null)
    {
        return $productRelationTransfer !== null && $productRelationTransfer->getIsActive();
    }
}
