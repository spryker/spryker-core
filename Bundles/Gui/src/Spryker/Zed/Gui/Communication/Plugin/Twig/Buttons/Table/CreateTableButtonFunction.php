<?php

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table;

class CreateTableButtonFunction extends AbstractTableFunction
{
    /**
     * @return string
     */
    protected function getButtonClass()
    {
        return 'btn-create';
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return '<i class="fa fa-plus"></i> ';
    }

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'createTableButton';
    }

}