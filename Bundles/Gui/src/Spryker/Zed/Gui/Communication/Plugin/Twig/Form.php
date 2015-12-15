<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Zed\Library\Twig\TwigFunction;

class Form extends TwigFunction
{

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'form';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($form) {
            return $form;
        };
    }

}
