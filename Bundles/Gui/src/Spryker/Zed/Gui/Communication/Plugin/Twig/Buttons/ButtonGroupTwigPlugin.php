<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class ButtonGroupTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const FUNCTION_NAME_GROUP_ACTION_BUTTONS = 'groupActionButtons';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction($this->getButtonGroupFunction());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getButtonGroupFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_GROUP_ACTION_BUTTONS, function (array $buttons, string $title, array $options = []) {
            if (!array_key_exists(ButtonGroupUrlGenerator::ICON, $options)) {
                $options[ButtonGroupUrlGenerator::ICON] = $this->getDefaultIcon();
            }

            if (!array_key_exists(ButtonGroupUrlGenerator::BUTTON_CLASS, $options)) {
                $options[ButtonGroupUrlGenerator::BUTTON_CLASS] = $this->getDefaultButtonClass();
            }

            $buttonGroupUrlGenerator = $this->getFactory()->createButtonGroupUrlGenerator($buttons, $title, $options);

            return $buttonGroupUrlGenerator->generate();
        }, ['is_safe' => ['html']]);
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
}
