<?php

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia;

class ViewActionButton extends AbstractActionButton
{

    /**
     * @return string
     */
    protected function getButtonClass()
    {
        return 'btn-info';
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        return '<i class="fa fa-caret-right"></i> ';
    }

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'viewActionButton';
    }

}
