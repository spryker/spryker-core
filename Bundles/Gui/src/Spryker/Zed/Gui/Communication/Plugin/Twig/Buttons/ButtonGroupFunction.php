<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons;

use Spryker\Shared\Twig\TwigFunction;

/**
 * @deprecated Use 'Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\ButtonGroupTwigPlugin` instead.
 */
class ButtonGroupFunction extends TwigFunction
{
    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($buttons, $title, $options = []) {
            if (!array_key_exists(ButtonGroupUrlGenerator::ICON, $options)) {
                $options[ButtonGroupUrlGenerator::ICON] = $this->getDefaultIcon();
            }

            if (!array_key_exists(ButtonGroupUrlGenerator::BUTTON_CLASS, $options)) {
                $options[ButtonGroupUrlGenerator::BUTTON_CLASS] = $this->getDefaultButtonClass();
            }

            $button = $this->createButtonUrlGenerator($buttons, $title, $options);

            return $button->generate();
        };
    }

    /**
     * @return string
     */
    protected function getDefaultButtonClass()
    {
        return 'btn-view';
    }

    /**
     * @return string
     */
    protected function getDefaultIcon()
    {
        return '<i class="fa fa-caret-right"></i> ';
    }

    /**
     * @param array $buttons
     * @param string $title
     * @param array $options
     *
     * @return \Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\ButtonGroupUrlGenerator
     */
    protected function createButtonUrlGenerator(array $buttons, $title, array $options)
    {
        return new ButtonGroupUrlGenerator($buttons, $title, $options);
    }

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'groupActionButtons';
    }
}
