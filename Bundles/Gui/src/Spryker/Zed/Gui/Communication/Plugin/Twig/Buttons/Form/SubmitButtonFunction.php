<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Form;

use Spryker\Shared\Twig\TwigFunction;
use Twig\Environment;

/**
 * @deprecated `\Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Form\SubmitButtonTwigPlugin` instead.
 */
class SubmitButtonFunction extends TwigFunction
{
    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'submit_button';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function (Environment $twigEnvironment, $value, array $attr = []) {
            return $twigEnvironment->render(
                '@Gui/Form/button/submit_button.twig',
                [
                    'value' => $value,
                    'attr' => $attr,
                ]
            );
        };
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();
        $options['needs_environment'] = true;

        return $options;
    }
}
