<?php

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table;

class BackTableButtonFunction extends AbstractTableFunction
{
    /**
     * @return string
     */
    protected function getButtonClass()
    {
        return 'btn-default';
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return '<i class="fa fa-angle-double-left"></i> ';
    }

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'backTableButton';
    }

}