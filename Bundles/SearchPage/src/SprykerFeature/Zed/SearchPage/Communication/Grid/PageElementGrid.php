<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Communication\Grid;

use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class PageElementGrid extends AbstractTable
{

    const ID_SEARCH_PAGE_ELEMENT = 'id_search_page_element';
    const ELEMENT_KEY = 'element_key';
    const IS_ELEMENT_ACTIVE = 'is_element_active';
    const TEMPLATE_NAME = 'template_name';
    const ATTRIBUTE_TYPE = 'attribute_type';
    const ATTRIBUTE_NAME = 'attribute_name';

    protected function configure(TableConfiguration $config)
    {
        // @todo: Implement configure() method.
    }

    protected function prepareData(TableConfiguration $config)
    {
        // @todo: Implement prepareData() method.
    }

    public function definePlugins()
    {
        $plugins = [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName(self::ID_SEARCH_PAGE_ELEMENT)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::ELEMENT_KEY)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::IS_ELEMENT_ACTIVE)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::TEMPLATE_NAME)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::ATTRIBUTE_NAME)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::ATTRIBUTE_TYPE)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }

}
