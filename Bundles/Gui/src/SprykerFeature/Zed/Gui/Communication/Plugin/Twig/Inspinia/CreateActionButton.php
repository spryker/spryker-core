<?php

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

class CreateActionButton extends TwigFunction
{

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'createActionButton';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function($url, $title) {
            return '<a class="btn btn-sm btn-success btn-outline" href="' . $url . '">' . $title . '</a>';
        };
    }

}
