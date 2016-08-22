<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductSearch\Communication\Controller\SearchPreferencesController;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class SearchPreferencesTable extends AbstractTable
{

    const COL_NAME = SpyProductAttributeKeyTableMap::COL_KEY;
    const COL_SUGGESTION_TERMS = 'suggestionTerms';
    const COL_COMPLETION_TERMS = 'completionTerms';
    const COL_FULL_TEXT = 'fullText';
    const COL_FULL_TEXT_BOOSTED = 'fullTextBoosted';
    const ACTION = 'action';

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
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader($this->getHeaderFields());
        $config->setSearchable($this->getSearchableFields());
        $config->setSortable($this->getSortableFields());

        $config->addRawColumn(self::ACTION);

        return $config;
    }

    /**
     * @return array
     */
    protected function getHeaderFields()
    {
        return [
            self::COL_NAME => 'Attribute name',
            self::COL_FULL_TEXT => 'Include for Full Text',
            self::COL_FULL_TEXT_BOOSTED => 'Include for Full Text Boosted',
            self::COL_SUGGESTION_TERMS => 'Include for Suggestion',
            self::COL_COMPLETION_TERMS => 'Include for Completion',
            self::ACTION => 'Action',
        ];
    }

    /**
     * @return array
     */
    protected function getSearchableFields()
    {
        return [
            self::COL_NAME,
        ];
    }

    /**
     * @return array
     */
    protected function getSortableFields()
    {
        return [
            self::COL_NAME,
            self::COL_FULL_TEXT,
            self::COL_FULL_TEXT_BOOSTED,
            self::COL_SUGGESTION_TERMS,
            self::COL_COMPLETION_TERMS,
        ];
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $result = [];

        $productAttributeKeys = $this->getProductAttributeKeys($config);

        foreach ($productAttributeKeys as $productAttributeKeyEntity) {
            $result[] = [
                self::COL_NAME => $productAttributeKeyEntity->getKey(),
                self::COL_FULL_TEXT => $this->boolToString($productAttributeKeyEntity->getVirtualColumn(self::COL_FULL_TEXT)),
                self::COL_FULL_TEXT_BOOSTED => $this->boolToString($productAttributeKeyEntity->getVirtualColumn(self::COL_FULL_TEXT_BOOSTED)),
                self::COL_SUGGESTION_TERMS => $this->boolToString($productAttributeKeyEntity->getVirtualColumn(self::COL_SUGGESTION_TERMS)),
                self::COL_COMPLETION_TERMS => $this->boolToString($productAttributeKeyEntity->getVirtualColumn(self::COL_COMPLETION_TERMS)),
                self::ACTION => $this->generateEditButton(
                    sprintf(
                        '/product-search/search-preferences/edit?%s=%d',
                        SearchPreferencesController::PARAM_ID,
                        $productAttributeKeyEntity->getIdProductAttributeKey()
                    ),
                    'Edit'
                ),
            ];
        }

        return $result;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKey[]
     */
    protected function getProductAttributeKeys(TableConfiguration $config)
    {
        $query = $this
            ->productSearchQueryContainer
            ->querySearchPreferencesTable();

        $productAttributeKey = $this->runQuery($query, $config, true);

        return $productAttributeKey;
    }

    /**
     * @param bool $boolValue
     *
     * @return string
     */
    protected function boolToString($boolValue)
    {
        return $boolValue ? 'yes' : 'no';
    }

}
