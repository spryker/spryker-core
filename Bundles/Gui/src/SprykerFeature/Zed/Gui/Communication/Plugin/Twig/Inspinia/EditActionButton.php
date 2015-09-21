<?php

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia;

class EditActionButton extends AbstractActionButton
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
        return '<i class="fa fa-pencil-square-o"></i> ';
    }

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'editActionButton';
    }

}
