<?php

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table;

class RemoveTableButtonFunction extends AbstractTableFunction
{
    /**
     * @return string
     */
    protected function getButtonClass()
    {
        return 'btn-remove';
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return '<i class="fa fa-trash"></i> ';
    }

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'removeTableButton';
    }

}