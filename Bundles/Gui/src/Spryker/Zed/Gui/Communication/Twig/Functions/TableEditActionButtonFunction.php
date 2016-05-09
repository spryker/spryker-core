<?php

namespace Spryker\Zed\Gui\Communication\Twig\Functions;

use Spryker\Zed\Gui\Communication\Twig\ActionButtons\TableActionButton;
use Spryker\Zed\Library\Twig\TwigFunction;

class TableEditActionButtonFunction extends TwigFunction
{
    protected function getFunctionName()
    {
        return 'tableEditButton';
    }

    protected function getFunction()
    {
        return function($url, $title, array $options = []) {

            return '-my link-';

            $anchor = new TableActionButton();

            return $anchor->generate('edit', $url, $title, $options);

//            $html = $this->generateAnchor($url, $options);
//            $html .= $this->getIcon();
//            $html .= $title;
//            $html .= '</a>';
//
//            return $html;
        };
    }
}