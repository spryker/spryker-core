<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductRelation\DataProvider;

use Spryker\Client\ProductRelation\ProductRelationClientInterface;
use Spryker\Shared\ProductRelation\ProductRelationTypes;
use Spryker\Yves\ProductRelation\Sorting\RelationSorterInterface;

class RelatedProductsDataProvider implements ProductRelationDataProviderInterface
{
    public const PARAMETER_ID_PRODUCT_ABSTRACT = 'idProductAbstract';

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
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\StorageProductAbstractRelationTransfer[]
     */
    public function buildTemplateData(array $parameters)
    {
        if (!isset($parameters[static::PARAMETER_ID_PRODUCT_ABSTRACT])) {
            return [];
        }

        $productRelationCollection = $this->productRelationClient->getProductRelationsByIdProductAbstract(
            $parameters[static::PARAMETER_ID_PRODUCT_ABSTRACT]
        );

        $productRelationTransfer = $this->extractProductRelationTransfer($productRelationCollection);
        if ($productRelationTransfer === null || !$productRelationTransfer->getIsActive()) {
            return [];
        }

        return $this->relationSorter->sort((array)$productRelationTransfer->getAbstractProducts());
    }

    /**
     * @return string
     */
    public function getAcceptedType()
    {
        return ProductRelationTypes::TYPE_RELATED_PRODUCTS;
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
}
