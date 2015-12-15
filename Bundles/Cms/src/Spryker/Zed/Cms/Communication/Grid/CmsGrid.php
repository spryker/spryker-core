<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Grid;

use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CmsGrid extends AbstractTable
{

    /**
     * @return void
     */
    protected function configure(TableConfiguration $config)
    {
        // @todo: Implement configure() method.
    }

    /**
     * @return void
     */
    protected function prepareData(TableConfiguration $config)
    {
        // @todo: Implement prepareData() method.
    }

    public function definePlugins()
    {
        return [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),
            $this->createDefaultColumn()
                ->setName('id_cms_page')
                ->sortable()
            ,
            $this->createDefaultColumn()
                ->setName('fk_template')
                ->filterable()
                ->sortable()
            ,
            $this->createDefaultColumn()
                ->setName('valid_from')
            ,
            $this->createDefaultColumn()
                ->setName('valid_to')
                ->filterable()
                ->sortable()
            ,
            $this->createDefaultColumn()
                ->setName('is_active')
                ->filterable()
                ->sortable()
            ,
        ];
    }

}
