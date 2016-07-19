<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business\Attribute;

use Orm\Zed\ProductManagement\Persistence\Map\SpyProductManagementAttributeValueTableMap;
use Orm\Zed\ProductManagement\Persistence\Map\SpyProductManagementAttributeValueTranslationTableMap;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface;

class AttributeReader implements AttributeReaderInterface
{

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface
     */
    protected $productManagementQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface $productManagementQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToGlossaryInterface $glossaryFacade
     */
    public function __construct(
        ProductManagementQueryContainerInterface $productManagementQueryContainer,
        ProductManagementToProductInterface $productFacade,
        ProductManagementToGlossaryInterface $glossaryFacade
    ) {
        $this->productManagementQueryContainer = $productManagementQueryContainer;
        $this->productFacade = $productFacade;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param int $idAttribute
     * @param string $idLocale
     * @param string $searchText
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getAttributeValues($idAttribute, $idLocale, $searchText = '', $offset = 0, $limit = 10)
    {
        $query = $this->productManagementQueryContainer->queryProductManagementAttributeValueWithTranslation($idAttribute, $idLocale);

        $searchText = trim($searchText);
        if ($searchText !== '') {
            $term = '%' . mb_strtoupper($searchText) . '%';

            $query->where('UPPER(' . SpyProductManagementAttributeValueTableMap::COL_VALUE . ') LIKE ?', $term, \PDO::PARAM_STR)
                ->_or()
                ->where('UPPER(' . SpyProductManagementAttributeValueTranslationTableMap::COL_TRANSLATION . ') LIKE ?', $term, \PDO::PARAM_STR);
        }

        return $query->find()->toArray();
    }

}
