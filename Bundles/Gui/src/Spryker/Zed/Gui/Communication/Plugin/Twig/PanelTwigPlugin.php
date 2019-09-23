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
        $twig->addFunction($this->getZedPanelFunction());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getZedPanelFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_PANEL, function (string $title, string $content, ?array $options = null, ?string $footer = null) {
            $defaultOptions = [
                'class' => 'default',
                'id' => false,
                'noWrap' => false,
                'collapsable' => false,
                'collapsed' => false,
            ];

            $options = array_merge($defaultOptions, (array)$options);

            $collapsable = '';
            $id = '';

            if ($options['collapsable']) {
                $collapsable = ' data-collapsable';

                if ($options['collapsed']) {
                    $options['class'] .= ' is:collapsed';
                }
            }

            if ($options['id']) {
                $id = ' id="' . $options['id'] . '"';
            }

            $html = '<section class="panel panel-' . $options['class'] . '"' . $id . $collapsable . '>';

            if ($title) {
                $html .= '<header class="panel-heading" data-collapse-trigger>';
                $html .= '<h1 class="panel-title">' . $title . '</h1>';
                $html .= '</header>';
            }

            if ($options['noWrap']) {
                $html .= $content;
            } else {
                $html .= '<div class="panel-body">' . $content . '</div>';
            }

            if ($footer) {
                $html .= '<footer class="panel-footer">' . $footer . '</footer>';
            }

            $html .= '</section>';

            return $html;
        }, ['is_safe' => ['html']]);
    }
}
