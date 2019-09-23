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
abstract class AbstractButtonTwig extends AbstractPlugin implements TwigPluginInterface
{
    protected const DEFAULT_CSS_CLASSES = 'undefined';

    /**
     * @return string
     */
    abstract protected function getFunctionName(): string;

    /**
     * @return string
     */
    abstract protected function getButtonClass(): string;

    /**
     * @return string
     */
    abstract protected function getIcon(): string;

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
        return new TwigFunction($this->getFunctionName(), function ($url, $title, $options = []) {
            if (!array_key_exists(ButtonUrlGenerator::ICON, $options)) {
                $options[ButtonUrlGenerator::ICON] = $this->getIcon();
            }

            if (!array_key_exists(ButtonUrlGenerator::BUTTON_CLASS, $options)) {
                $options[ButtonUrlGenerator::BUTTON_CLASS] = $this->getButtonClass();
            }

            if (!array_key_exists(ButtonUrlGenerator::DEFAULT_CSS_CLASSES, $options)) {
                $options[ButtonUrlGenerator::DEFAULT_CSS_CLASSES] = static::DEFAULT_CSS_CLASSES;
            }

            $buttonUrlGenerator = $this->createButtonUrlGenerator($url, $title, $options);

            return $buttonUrlGenerator->generate();
        }, ['is_safe' => ['html']]);
    }

    /**
     * @param string $url
     * @param string $title
     * @param array $options
     *
     * @return \Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\UrlGeneratorInterface
     */
    protected function createButtonUrlGenerator($url, $title, array $options): UrlGeneratorInterface
    {
        return new ButtonUrlGenerator($url, $title, $options);
    }
}
