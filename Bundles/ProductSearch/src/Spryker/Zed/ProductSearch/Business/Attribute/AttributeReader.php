<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Attribute;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use PDO;
use Propel\Runtime\Formatter\ArrayFormatter;
use Spryker\Zed\ProductSearch\Business\Transfer\ProductAttributeTransferMapperInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;

class AttributeReader implements AttributeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected $productSearchQueryContainer;

    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductSearch\Business\Transfer\ProductAttributeTransferMapperInterface
     */
    protected $productAttributeTransferMapper;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQueryContainer
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductSearch\Business\Transfer\ProductAttributeTransferMapperInterface $productAttributeTransferMapper
     */
    public function __construct(
        ProductSearchQueryContainerInterface $productSearchQueryContainer,
        ProductSearchToLocaleInterface $localeFacade,
        ProductAttributeTransferMapperInterface $productAttributeTransferMapper
    ) {
        $this->productSearchQueryContainer = $productSearchQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->productAttributeTransferMapper = $productAttributeTransferMapper;
    }

    /**
     * @param int $idProductSearchAttribute
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer|null
     */
    public function getAttribute($idProductSearchAttribute)
    {
        $attributeData = $this->getAttributeData($idProductSearchAttribute);

        if (!$attributeData) {
            return null;
        }

        return $this->productAttributeTransferMapper
            ->convertProductAttribute($attributeData);
    }

    /**
     * @param int $idProductSearchAttribute
     *
     * @return array<string, mixed>
     */
    protected function getAttributeData($idProductSearchAttribute): array
    {
        /** @var array|null $attributeData */
        $attributeData = $this->productSearchQueryContainer
            ->queryFilterPreferencesTable()
            ->setFormatter(ArrayFormatter::class)
            ->findOneByIdProductSearchAttribute($idProductSearchAttribute);

        return $attributeData;
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array<string>
     */
    public function suggestUnusedKeys($searchText = '', $limit = 10)
    {
        $query = $this->productSearchQueryContainer
            ->queryUnusedProductAttributeKeys();

        /** @var array<string> $suggestedUnusedKeys */
        $suggestedUnusedKeys = $this->applySearchParamsToQuery($query, $searchText, $limit)
            ->find();

        return $suggestedUnusedKeys;
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array<string>
     */
    public function suggestKeys($searchText = '', $limit = 10)
    {
        $query = $this->productSearchQueryContainer
            ->queryAllProductAttributeKeys();

        /** @var array<string> $suggestedKeys */
        $suggestedKeys = $this->applySearchParamsToQuery($query, $searchText, $limit)
            ->find();

        return $suggestedKeys;
    }

    /**
     * @return array<\Generated\Shared\Transfer\ProductSearchAttributeTransfer>
     */
    public function getAttributeList()
    {
        $productSearchAttributes = [];
        $productSearchAttributeCollection = $this->productSearchQueryContainer
            ->queryFilterPreferencesTable()
            ->orderByPosition()
            ->setFormatter(ArrayFormatter::class)
            ->find();

        foreach ($productSearchAttributeCollection as $productSearchAttribute) {
            $productSearchAttributes[] = $this->productAttributeTransferMapper
                ->convertProductAttribute($productSearchAttribute);
        }

        return $productSearchAttributes;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery $query
     * @param string $searchText
     * @param int $limit
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    protected function applySearchParamsToQuery(SpyProductAttributeKeyQuery $query, $searchText, $limit)
    {
        $query->limit($limit)
            ->setFormatter(new PropelArraySetFormatter());

        $searchText = trim($searchText);
        if ($searchText !== '') {
            $term = '%' . mb_strtoupper($searchText) . '%';

            $query->where('UPPER(' . SpyProductAttributeKeyTableMap::COL_KEY . ') LIKE ?', $term, PDO::PARAM_STR);
        }

        return $query;
    }
}
