<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Form\DataProvider;

use Spryker\Zed\ProductSearch\Communication\Form\SearchPreferencesForm;
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
     * @param int $idProductAttributeKey
     *
     * @return array
     */
    public function getData($idProductAttributeKey)
    {
        $productAttributeKeyEntity = $this
            ->productSearchQueryContainer
            ->querySearchPreferencesTable()
            ->filterByIdProductAttributeKey($idProductAttributeKey)
            ->findOne();

        return [
            SearchPreferencesForm::FIELD_ATTRIBUTE_NAME => $productAttributeKeyEntity->getKey(),
            SearchPreferencesForm::FIELD_FULL_TEXT => $productAttributeKeyEntity->getVirtualColumn(SearchPreferencesForm::FIELD_FULL_TEXT),
            SearchPreferencesForm::FIELD_FULL_TEXT_BOOSTED => $productAttributeKeyEntity->getVirtualColumn(SearchPreferencesForm::FIELD_FULL_TEXT_BOOSTED),
            SearchPreferencesForm::FIELD_SUGGESTION_TERMS => $productAttributeKeyEntity->getVirtualColumn(SearchPreferencesForm::FIELD_SUGGESTION_TERMS),
            SearchPreferencesForm::FIELD_COMPLETION_TERMS => $productAttributeKeyEntity->getVirtualColumn(SearchPreferencesForm::FIELD_COMPLETION_TERMS),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

}
