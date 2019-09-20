<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons;

use Spryker\Shared\Twig\TwigFunction;

/**
 * @deprecated Use `\Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table\AbstractButtonTwig` instead.
 */
abstract class AbstractButtonFunction extends TwigFunction
{
    public const DEFAULT_CSS_CLASSES = 'undefined';

    /**
     * @return string
     */
    abstract protected function getButtonClass();

    /**
     * @return string
     */
    abstract protected function getIcon();

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($url, $title, $options = []) {
            if (!array_key_exists(ButtonUrlGenerator::ICON, $options)) {
                $options[ButtonUrlGenerator::ICON] = $this->getIcon();
            }

            if (!array_key_exists(ButtonUrlGenerator::BUTTON_CLASS, $options)) {
                $options[ButtonUrlGenerator::BUTTON_CLASS] = $this->getButtonClass();
            }

            if (!array_key_exists(ButtonUrlGenerator::DEFAULT_CSS_CLASSES, $options)) {
                $options[ButtonUrlGenerator::DEFAULT_CSS_CLASSES] = static::DEFAULT_CSS_CLASSES;
            }

            $button = $this->createButtonUrlGenerator($url, $title, $options);

            return $button->generate();
        };
    }

    /**
     * @param string $url
     * @param string $title
     * @param array $options
     *
     * @return \Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\ButtonUrlGenerator
     */
    protected function createButtonUrlGenerator($url, $title, array $options)
    {
        $button = new ButtonUrlGenerator($url, $title, $options);

        return $button;
    }
}
