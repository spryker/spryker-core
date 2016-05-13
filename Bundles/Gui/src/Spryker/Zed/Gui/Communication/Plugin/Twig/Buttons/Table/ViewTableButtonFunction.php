<?php

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table;

class ViewTableButtonFunction extends AbstractTableFunction
{
    /**
     * @return string
     */
    protected function getButtonClass()
    {
        return 'btn-success';
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return '<i class="fa fa-eye"></i> ';
    }

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'viewTableButton';
    }

}