<?php

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

class BackActionButton extends TwigFunction
{

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'backActionButton';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function($url, $title) {
            return '<a class="btn btn-sm btn-primary btn-outline" href="' . $url . '">' . $title . '</a>';
        };
    }

}
