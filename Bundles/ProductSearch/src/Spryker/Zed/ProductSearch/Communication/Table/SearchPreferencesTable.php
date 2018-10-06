<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductSearch\Communication\Controller\SearchPreferencesController;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class SearchPreferencesTable extends AbstractTable
{
    public const COL_NAME = SpyProductAttributeKeyTableMap::COL_KEY;
    public const COL_SUGGESTION_TERMS = 'suggestionTerms';
    public const COL_COMPLETION_TERMS = 'completionTerms';
    public const COL_FULL_TEXT = 'full_Text';
    public const COL_FULL_TEXT_BOOSTED = 'fullTextBoosted';
    public const ACTIONS = 'actions';

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

        $config->addRawColumn(self::ACTIONS);

        return $config;
    }

    /**
     * @return array
     */
    protected function getHeaderFields()
    {
        return [
            self::COL_NAME => 'Attribute key',
            self::COL_FULL_TEXT => 'Include for full text',
            self::COL_FULL_TEXT_BOOSTED => 'Include for full text boosted',
            self::COL_SUGGESTION_TERMS => 'Include for suggestion',
            self::COL_COMPLETION_TERMS => 'Include for completion',
            self::ACTIONS => 'Actions',
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
                self::ACTIONS => $this->getActions($productAttributeKeyEntity),
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

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAttributeKey $productAttributeKeyEntity
     *
     * @return string
     */
    protected function getActions(SpyProductAttributeKey $productAttributeKeyEntity)
    {
        $actions = [
            $this->generateEditButton(
                sprintf(
                    '/product-search/search-preferences/edit?%s=%d',
                    SearchPreferencesController::PARAM_ID,
                    $productAttributeKeyEntity->getIdProductAttributeKey()
                ),
                'Edit'
            ),
            $this->generateRemoveButton(
                sprintf(
                    '/product-search/search-preferences/clean?%s=%d',
                    SearchPreferencesController::PARAM_ID,
                    $productAttributeKeyEntity->getIdProductAttributeKey()
                ),
                'Deactivate all'
            ),
        ];

        return implode(' ', $actions);
    }
}
