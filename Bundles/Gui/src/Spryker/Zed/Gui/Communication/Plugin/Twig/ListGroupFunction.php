<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Shared\Twig\TwigFunction;

/**
 * @deprecated Use `Spryker\Zed\Gui\Communication\Plugin\Twig\ListGroupTwigPlugin` instead.
 */
class ListGroupFunction extends TwigFunction
{
    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'listGroup';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function (array $items) {
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
        };
    }
}
