<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Table;

use Orm\Zed\Product\Persistence\Map\SpyProductAttributesMetadataTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductSearch\Communication\Controller\SearchPreferencesController;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class SearchPreferencesTable extends AbstractTable
{

    const COL_NAME = SpyProductAttributesMetadataTableMap::COL_KEY;
    const COL_PROPERTY_TYPE = 'type';
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
            self::COL_PROPERTY_TYPE => 'Type',
            self::COL_FULL_TEXT => 'Include for Full Text',
            self::COL_FULL_TEXT_BOOSTED => 'Include for Full Text Boosted',
            self::COL_SUGGESTION_TERMS => 'Include for Suggestion',
            self::COL_COMPLETION_TERMS => 'Include for Suggestion',
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
            self::COL_PROPERTY_TYPE,
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

        $productAttributesMetadata = $this->getProductAttributesMetadata($config);

        foreach ($productAttributesMetadata as $productAttributesMetadataEntity) {
            $result[] = [
                self::COL_NAME => $productAttributesMetadataEntity->getKey(),
                self::COL_PROPERTY_TYPE => $productAttributesMetadataEntity->getSpyProductAttributeType()->getName(),
                self::COL_FULL_TEXT => $this->boolToString($productAttributesMetadataEntity->getVirtualColumn(self::COL_FULL_TEXT)),
                self::COL_FULL_TEXT_BOOSTED => $this->boolToString($productAttributesMetadataEntity->getVirtualColumn(self::COL_FULL_TEXT_BOOSTED)),
                self::COL_SUGGESTION_TERMS => $this->boolToString($productAttributesMetadataEntity->getVirtualColumn(self::COL_SUGGESTION_TERMS)),
                self::COL_COMPLETION_TERMS => $this->boolToString($productAttributesMetadataEntity->getVirtualColumn(self::COL_COMPLETION_TERMS)),
                self::ACTION => $this->generateEditButton(
                    sprintf(
                        '/product-search/search-preferences/edit?%s=%d',
                        SearchPreferencesController::PARAM_ID,
                        $productAttributesMetadataEntity->getIdProductAttributesMetadata()
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
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributesMetadata[]
     */
    protected function getProductAttributesMetadata(TableConfiguration $config)
    {
        $query = $this
            ->productSearchQueryContainer
            ->querySearchPreferencesTable();

        $productAttributesMetadata = $this->runQuery($query, $config, true);

        return $productAttributesMetadata;
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
