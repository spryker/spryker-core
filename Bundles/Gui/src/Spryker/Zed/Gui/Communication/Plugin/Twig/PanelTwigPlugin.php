<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class PanelTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FUNCTION_NAME_PANEL = 'panel';

    /**
     * {@inheritDoc}
     * - Extends twig with "panel" function to create bordered box with some padding around it's content.
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
        $twig->addFunction($this->getZedPanelFunction($twig));

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFunction
     */
    protected function getZedPanelFunction(Environment $twig): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_PANEL, function (string $title, string $content, ?array $options = null, ?string $footer = null) use ($twig) {
            $defaultOptions = [
                'class' => 'default',
                'id' => false,
                'noWrap' => false,
                'collapsable' => false,
                'collapsed' => false,
            ];

            $options = array_merge($defaultOptions, (array)$options);

            return $twig->render(
                $this->getConfig()->getDefaultPanelTemplatePath(),
                [
                    'title' => $title,
                    'content' => $content,
                    'options' => $options,
                    'footer' => $footer,
                ]
            );
        }, ['is_safe' => ['html']]);
    }
}
