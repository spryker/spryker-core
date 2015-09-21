<?php

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

abstract class AbstractActionButton extends TwigFunction
{
    const PARAM_ID = 'id';
    const PARAM_CLASS = 'class';

    /**
     * @return string
     */
    protected abstract function getButtonClass();

    /**
     * @return string
     */
    protected abstract function getIcon();

    /**
     * @param array $options
     *
     * @return string
     */
    protected function getId(array $options)
    {
        $id = '';
        if (array_key_exists(self::PARAM_ID, $options)) {
            $id = ' id="' . $options[self::PARAM_ID] . '"';
        }

        return $id;
    }

    /**
     * @param array $options
     *
     * @return string
     */
    protected function getClass(array $options = [])
    {
        $extraClasses = '';
        if (array_key_exists(self::PARAM_CLASS, $options)) {
            $extraClasses = ' ' . $options[self::PARAM_CLASS];
        }

        return ' class="btn btn-sm btn-outline '
            . $this->getButtonClass()
            . $extraClasses
            . '"'
        ;
    }

    /**
     * @param string $url
     * @param array $options
     *
     * @return string
     */
    protected function generateAnchor($url, array $options = [])
    {
        return '<a' . $this->getClass($options) . $this->getId($options) . ' href="' . $url . '">';
    }

    /**
     * @return callable
     */
    protected function getFunction()
    {
        $button = $this;

        return function($url, $title, $options = []) use ($button) {

            $html = $button->generateAnchor($url, $options);
            $html .= $this->getIcon();
            $html .= $title;
            $html .= '</a>';

            return $html;
        };
    }
}
