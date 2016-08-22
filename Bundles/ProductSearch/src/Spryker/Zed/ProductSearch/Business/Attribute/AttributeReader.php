<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Attribute;

use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeValueTableMap;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeValueTranslationTableMap;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeValueQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Spryker\Zed\ProductSearch\Business\Transfer\ProductAttributeTransferGeneratorInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;
use Spryker\Zed\Propel\Business\Formatter\PropelArraySetFormatter;

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
     * @var \Spryker\Zed\ProductSearch\Business\Transfer\ProductAttributeTransferGeneratorInterface
     */
    protected $productAttributeTransferGenerator;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQueryContainer
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductSearch\Business\Transfer\ProductAttributeTransferGeneratorInterface $productAttributeTransferGenerator
     */
    public function __construct(
        ProductSearchQueryContainerInterface $productSearchQueryContainer,
        ProductSearchToLocaleInterface $localeFacade,
        ProductAttributeTransferGeneratorInterface $productAttributeTransferGenerator
    ) {
        $this->productSearchQueryContainer = $productSearchQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->productAttributeTransferGenerator = $productAttributeTransferGenerator;
    }

    /**
     * @param int $idProductSearchAttribute
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer|null
     */
    public function getAttribute($idProductSearchAttribute)
    {
        $attributeEntity = $this->getAttributeEntity($idProductSearchAttribute);

        if (!$attributeEntity) {
            return null;
        }

        return $this->productAttributeTransferGenerator
            ->convertProductAttribute($attributeEntity);
    }

    /**
     * @param int $idProductSearchAttribute
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute|null
     */
    protected function getAttributeEntity($idProductSearchAttribute)
    {
        return $this->productSearchQueryContainer
            ->queryFilterPreferencesTable()
            ->findOneByIdProductSearchAttribute($idProductSearchAttribute);
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestUnusedKeys($searchText = '', $limit = 10)
    {
        $query = $this->productSearchQueryContainer
            ->queryUnusedProductAttributeKeys()
            ->limit($limit)
            ->setFormatter(new PropelArraySetFormatter());

        $searchText = trim($searchText);
        if ($searchText !== '') {
            $term = '%' . mb_strtoupper($searchText) . '%';

            $query->where('UPPER(' . SpyProductAttributeKeyTableMap::COL_KEY . ') LIKE ?', $term, \PDO::PARAM_STR);
        }

        return $query->find();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer[]
     */
    public function getAttributeList()
    {
        $productSearchAttributes = [];
        $productSearchAttributeEntities = $this->productSearchQueryContainer
            ->queryFilterPreferencesTable()
            ->orderByPosition()
            ->find();

        foreach ($productSearchAttributeEntities as $productSearchAttributeEntity) {
            $productSearchAttributes[] = $this->productAttributeTransferGenerator
                ->convertProductAttribute($productSearchAttributeEntity);
        }

        return $productSearchAttributes;
    }

}
