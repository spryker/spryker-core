<?php

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia;

class BackActionButton extends AbstractActionButton
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
