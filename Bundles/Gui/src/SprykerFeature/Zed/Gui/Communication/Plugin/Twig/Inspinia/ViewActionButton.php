<?php

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

class ViewActionButton extends TwigFunction
{

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'viewActionButton';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function($url, $title) {
            return '<a class="btn btn-sm btn-info btn-outline" href="' . $url . '">' . $title . '</a>';
        };
    }

}
