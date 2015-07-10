<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Twig;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

class Panel extends TwigFunction
{

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'panel';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        return function ($title, $content, array $options = null, $footer = null) {
            $defaultOptions = [
                'class' => 'default',
                'id' => false,
                'noWrap' => false,
                'collapsable' => false,
                'collapsed' => false,
            ];

            $options = array_merge($defaultOptions, (array) $options);

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
                $html .= '<h1 class="panel-title">' . __($title) . '</h1>';
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
        };
    }

}
