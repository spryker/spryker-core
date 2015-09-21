<?php

namespace SprykerFeature\Zed\Gui\Communication\Plugin\Twig\Inspinia;

use SprykerFeature\Zed\Library\Twig\TwigFunction;

abstract class AbstractActionButton extends TwigFunction
{
    protected abstract function getButtonClass();

    protected abstract function getIcon();

    /**
     * @param array $options
     *
     * @return string
     */
    protected function getId(array $options)
    {
        $id = '';
        if (array_key_exists('id', $options)) {
            $id = ' id="' . $options['id'] . '"';
        }

        return $id;
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function getClass($className)
    {
        return ' class="btn btn-sm btn-outline ' . $className . '"';
    }

    /**
     * @param string $url
     * @param array $options
     *
     * @return string
     */
    protected function generateAnchor($url, array $options = [])
    {
        $buttonClass = $this->getButtonClass();

        $html = '<a' . $this->getClass($buttonClass, $options);
        $html .= $this->getId($options);
        $html .= ' href="' . $url . '">';

        return $html;
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
