<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

class Button extends TwigFunction
{

    /**
     * @return string
     */
    protected function getFunctionName()
    {
        return 'button';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        $button = $this;

        return function ($title, array $options = []) use ($button) {
            $id = $button->getId($title, $options);
            $class = $button->getClass($options);
            $html = '<button class="' . $class . '" id="' . $id . '">' . $title . '</button>';

            return $html;
        };
    }

    /**
     * @param $title
     * @param array $options
     *
     * @return string
     */
    protected function getId($title, array $options)
    {
        if (array_key_exists('id', $options)) {
            $id = $options['id'];
        } else {
            $filter = new \Zend_Filter_Word_SeparatorToDash();
            $id = strtolower($filter->filter($title));
        }

        return $id;
    }

    /**
     * @param array $options
     *
     * @return string
     */
    protected function getClass(array $options)
    {
        $class = 'btn btn-primary';
        if (array_key_exists('class', $options)) {
            $class .= ' ' . $options['class'];
        }

        return $class;
    }

}
