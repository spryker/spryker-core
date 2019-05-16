<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Form\DataProvider;

use Spryker\Zed\ProductSearch\Communication\Form\SearchPreferencesForm;
use Spryker\Zed\ProductSearch\Communication\Table\SearchPreferencesTable;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class SearchPreferencesDataProvider
{
    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected $productSearchQueryContainer;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQueryContainer
     */
    public function __construct(ProductSearchQueryContainerInterface $productSearchQueryContainer)
    {
        $this->productSearchQueryContainer = $productSearchQueryContainer;
    }

    /**
     * @param int|null $idProductAttributeKey
     *
     * @return array
     */
    public function getData($idProductAttributeKey = null)
    {
        if (!$idProductAttributeKey) {
            return [];
        }

        $productAttributeKeyEntity = $this
            ->productSearchQueryContainer
            ->querySearchPreferencesTable()
            ->filterByIdProductAttributeKey($idProductAttributeKey)
            ->findOne();
        if ($productAttributeKeyEntity === null) {
            return [];
        }

        return [
            SearchPreferencesForm::FIELD_ID_PRODUCT_ATTRIBUTE_KEY => $idProductAttributeKey,
            SearchPreferencesForm::FIELD_KEY => $productAttributeKeyEntity->getKey(),
            SearchPreferencesForm::FIELD_FULL_TEXT => $productAttributeKeyEntity->getVirtualColumn(SearchPreferencesTable::COL_FULL_TEXT),
            SearchPreferencesForm::FIELD_FULL_TEXT_BOOSTED => $productAttributeKeyEntity->getVirtualColumn(SearchPreferencesForm::FIELD_FULL_TEXT_BOOSTED),
            SearchPreferencesForm::FIELD_SUGGESTION_TERMS => $productAttributeKeyEntity->getVirtualColumn(SearchPreferencesForm::FIELD_SUGGESTION_TERMS),
            SearchPreferencesForm::FIELD_COMPLETION_TERMS => $productAttributeKeyEntity->getVirtualColumn(SearchPreferencesForm::FIELD_COMPLETION_TERMS),
        ];
    }

    /**
     * @param int|null $idProductAttributeKey
     *
     * @return array
     */
    public function getOptions($idProductAttributeKey = null)
    {
        $options = [
            SearchPreferencesForm::OPTION_IS_UPDATE => ($idProductAttributeKey > 0),
        ];

        return $options;
    }
}
