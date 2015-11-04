<?php

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia;

class CreateActionButton extends AbstractActionButton
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
        return '<i class="fa fa-plus"></i> ';
    }

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'createActionButton';
    }

}
