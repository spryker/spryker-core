<?php

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table;

class EditTableButtonFunction extends AbstractTableFunction
{
    /**
     * @return string
     */
    protected function getButtonClass()
    {
        return 'btn-warning';
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return '<i class="fa fa-pencil-square-o"></i> ';
    }

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'editTableButton';
    }

}