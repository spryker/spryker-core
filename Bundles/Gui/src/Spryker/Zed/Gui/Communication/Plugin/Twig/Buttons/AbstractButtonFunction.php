<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons;

use Spryker\Shared\Twig\TwigFunction;

abstract class AbstractButtonFunction extends TwigFunction
{

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
            $options[ButtonUrlGenerator::ICON] = $this->getIcon();
            $options[ButtonUrlGenerator::BUTTON_CLASS] = $this->getButtonClass();
            $options[ButtonUrlGenerator::DEFAULT_CSS_CLASSES] = static::DEFAULT_CSS_CLASSES;

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
