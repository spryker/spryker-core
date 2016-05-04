<?php

namespace Spryker\Zed\Search\Communication\Table;

use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class FiltersTable extends AbstractTable
{
    const NAME = 'name';
    const TYPE = 'type';
    const FILTER_TYPE = 'filter_type';
    const INCLUDE_FOR_SUGGESTION = 'include_for_suggestion';
    const INCLUDE_FOR_SORTING = 'include_for_sorting';
    const INCLUDE_FOR_FULL_TEXT = 'include_for_full_text';
    const INCLUDE_FOR_FULL_TEXT_BOOSTED = 'include_for_full_text_boosted';
    const ACTION = 'action';

    /**
     * @param TableConfiguration $config
     * @return TableConfiguration
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
     * @param TableConfiguration $config
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $data = [];

        for ($i=1; $i<=25; $i++) {
            $data[] = [
                self::NAME => 'Attribute name ' . $i,
                self::TYPE => 'Type ' . $i,
                self::FILTER_TYPE => 'Filter Type',
                self::INCLUDE_FOR_SUGGESTION => 'Include for Suggestion',
                self::INCLUDE_FOR_SORTING => 'Include for Sorting',
                self::INCLUDE_FOR_FULL_TEXT => 'Include for Full Text',
                self::INCLUDE_FOR_FULL_TEXT_BOOSTED => 'Include for Full Text Boosted',
                self::ACTION => $this->generateEditButton(
                    '/search/filters/edit?id-filter=' . $i,
                    'Edit'
                ),
            ];
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getHeaderFields()
    {
        return [
            self::NAME => 'Attribute name',
            self::TYPE => 'Type',
            self::FILTER_TYPE => 'Filter Type',
            self::INCLUDE_FOR_SUGGESTION => 'Include for Suggestion',
            self::INCLUDE_FOR_SORTING => 'Include for Sorting',
            self::INCLUDE_FOR_FULL_TEXT => 'Include for Full Text',
            self::INCLUDE_FOR_FULL_TEXT_BOOSTED => 'Include for Full Text Boosted',
            self::ACTION => 'Action',
        ];
    }

    /**
     * @return array
     */
    protected function getSearchableFields()
    {
        return [
            self::NAME,
            self::TYPE,
            self::FILTER_TYPE,
            self::INCLUDE_FOR_SUGGESTION,
            self::INCLUDE_FOR_SORTING,
            self::INCLUDE_FOR_FULL_TEXT,
            self::INCLUDE_FOR_FULL_TEXT_BOOSTED,
        ];
    }

    /**
     * @return array
     */
    protected function getSortableFields()
    {
        return [
            self::NAME,
            self::TYPE,
            self::FILTER_TYPE,
            self::INCLUDE_FOR_SUGGESTION,
            self::INCLUDE_FOR_SORTING,
            self::INCLUDE_FOR_FULL_TEXT,
            self::INCLUDE_FOR_FULL_TEXT_BOOSTED,
        ];
    }
}
