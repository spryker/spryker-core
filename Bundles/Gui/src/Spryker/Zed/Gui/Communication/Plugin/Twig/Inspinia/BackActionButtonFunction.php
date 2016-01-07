<?php

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Inspinia;

class BackActionButtonFunction extends AbstractActionButtonFunction
{

    /**
     * @return string
     */
    protected function getButtonClass()
    {
        return 'btn-primary';
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
        return 'backActionButton';
    }

}
