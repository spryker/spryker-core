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
class ListGroupTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FUNCTION_NAME_LIST_GROUP = 'listGroup';

    /**
     * {@inheritDoc}
     * - Extends twig with "listGroup" function to generate custom list group for displaying a series of content.
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
        $twig->addFunction($this->getZedListGroupFunction());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getZedListGroupFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_LIST_GROUP, function (array $items) {
            if (is_array(array_values($items)[0])) {
                $html = '<div class="list-group">';

                foreach ($items as $item) {
                    $class = '';
                    $target = '';
                    $data = '';
                    $extras = '';

                    if (array_key_exists('class', $item)) {
                        $class = ' ' . $item['class'];
                    }

                    if (array_key_exists('target', $item) && !is_array($item['target'])) {
                        $target = ' target="' . $target . '"';
                    }

                    if (array_key_exists('extras', $item) && is_array($item['extras'])) {
                        foreach ($item['extras'] as $key => $value) {
                            if (is_array($value)) {
                                $value = json_encode($value);
                            }
                            $extras .= ' ' . $key . '="' . htmlentities($value) . '"';
                        }
                    }

                    if (array_key_exists('data', $item) && is_array($item['data'])) {
                        foreach ($item['data'] as $key => $value) {
                            if (is_array($value)) {
                                $value = json_encode($value);
                            }
                            $data .= ' data-' . $key . '="' . htmlentities($value);
                        }
                    }

                    $html .= '<a href="' . $item['href'] . '" class="list-group-item' . $class . '"' . $target . $data . $extras . '>';
                    $html .= $item['label'];
                    $html .= '</a>';
                }

                $html .= '</div>';
            } else {
                $html = '<ul class="list-group">';

                foreach ($items as $item) {
                    $html .= '<li class="list-group-item">' . $item;
                }

                $html .= '</ul>';
            }

            return $html;
        }, ['is_safe' => ['html']]);
    }
}
