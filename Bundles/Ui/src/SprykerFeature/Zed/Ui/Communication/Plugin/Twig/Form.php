<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Twig;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

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
