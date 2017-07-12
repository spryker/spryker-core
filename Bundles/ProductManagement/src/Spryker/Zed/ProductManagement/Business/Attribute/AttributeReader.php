<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeValueTableMap;
use Orm\Zed\ProductAttribute\Persistence\Map\SpyProductManagementAttributeValueTranslationTableMap;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery;
use PDO;
use Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferMapperInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;

class AttributeReader implements AttributeReaderInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferMapperInterface
     */
    protected $productAttributeTransferMapper;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductManagement\Business\Transfer\ProductAttributeTransferMapperInterface $productAttributeTransferGenerator
     */
    public function __construct(
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductManagementToLocaleInterface $localeFacade,
        ProductAttributeTransferMapperInterface $productAttributeTransferGenerator
    ) {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->productAttributeTransferMapper = $productAttributeTransferGenerator;
    }

    /**
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getAttributeValueSuggestions($idProductManagementAttribute, $idLocale, $searchText = '', $offset = 0, $limit = 10)
    {
        $query = $this->productManagementQueryContainer
            ->queryProductManagementAttributeValueWithTranslation($idProductManagementAttribute, $idLocale);

        $this->updateQuerySearchTextConditions($searchText, $query);

        $results = [];
        foreach ($query->find() as $attributeEntity) {
            $data = $attributeEntity->toArray();
            $title = trim($data['translation']);
            $title = ($title === '') ? $attributeEntity->getValue() : $title;

            $results[] = [
                'id' => $attributeEntity->getIdProductManagementAttributeValue(),
                'text' => $title,
            ];
        }

        return $results;
    }

    /**
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     *
     * @return int
     */
    public function getAttributeValueSuggestionsCount($idProductManagementAttribute, $idLocale, $searchText = '')
    {
        $query = $this->productManagementQueryContainer
            ->queryProductManagementAttributeValueWithTranslation($idProductManagementAttribute, $idLocale);

        $this->updateQuerySearchTextConditions($searchText, $query);

        return $query->count();
    }

    /**
     * @param string $searchText
     * @param \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueQuery $query
     *
     * @return void
     */
    protected function updateQuerySearchTextConditions($searchText, SpyProductManagementAttributeValueQuery $query)
    {
        $searchText = trim($searchText);
        if ($searchText !== '') {
            $term = '%' . mb_strtoupper($searchText) . '%';

            $query
                ->where('UPPER(' . SpyProductManagementAttributeValueTableMap::COL_VALUE . ') LIKE ?', $term, PDO::PARAM_STR)
                ->_or()
                ->where('UPPER(' . SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION . ') LIKE ?', $term, PDO::PARAM_STR);
        }
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute|null
     */
    protected function getAttributeEntity($idProductManagementAttribute)
    {
        return $this->productManagementQueryContainer
            ->queryProductManagementAttribute()
            ->findOneByIdProductManagementAttribute($idProductManagementAttribute);
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestUnusedKeys($searchText = '', $limit = 10)
    {
        $query = $this->productManagementQueryContainer
            ->queryUnusedProductAttributeKeys()
            ->limit($limit)
            ->setFormatter(new PropelArraySetFormatter());

        $searchText = trim($searchText);
        if ($searchText !== '') {
            $term = '%' . mb_strtoupper($searchText) . '%';

            $query->where('UPPER(' . SpyProductAttributeKeyTableMap::COL_KEY . ') LIKE ?', $term, PDO::PARAM_STR);
        }

        return $query->find();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection()
    {
        $collection = $this->productManagementQueryContainer
            ->queryProductManagementAttribute()
            ->innerJoinSpyProductAttributeKey()
            ->find();

        return $this->productAttributeTransferMapper->convertProductAttributeCollection($collection);
    }

}
