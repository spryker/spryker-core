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
            $html = '<a class="btn btn-sm btn-success btn-outline" href="' . $url . '">';
            $html .= '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> ';
            $html .= $title;
            $html .= '</a>';

            return $html;
        };
    }

}
