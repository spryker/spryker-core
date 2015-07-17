<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Twig;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

class ListGroup extends TwigFunction
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
