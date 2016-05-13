<?php

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons;

class ButtonUrlGenerator
{
    const PARAM_ID = 'id';
    const PARAM_CLASS = 'class';
    const DEFAULT_CSS_CLASSES = 'default_css_classes';
    const BUTTON_CLASS = 'button_class';
    const ICON = 'icon';

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param string $url
     * @param string $title
     * @param array $options
     */
    public function __construct($url, $title, array $options)
    {
        $this->url = $url;
        $this->title = $title;
        $this->options = $options;
    }

    public function generate()
    {
        $html = $this->generateAnchor($this->url, $this->options);
        $html .= $this->options[self::ICON];
        $html .= $this->title;
        $html .= '</a>';

        return $html;
    }

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
     * @return string
     */
    protected function getClass()
    {
        $extraClasses = '';
        if (array_key_exists(self::PARAM_CLASS, $this->options)) {
            $extraClasses = ' ' . $this->options[self::PARAM_CLASS];
        }

        return ' class="btn '
        . $this->options[self::DEFAULT_CSS_CLASSES]
        . ' '
        . $this->options[self::BUTTON_CLASS]
        . $extraClasses
        . '"';
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
}