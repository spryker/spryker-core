<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons;

class ButtonUrlGenerator
{
    public const PARAM_ID = 'id';
    public const PARAM_CLASS = 'class';
    public const DEFAULT_CSS_CLASSES = 'default_css_classes';
    public const BUTTON_CLASS = 'button_class';
    public const ICON = 'icon';
    public const CUSTOM_OPTIONS = [
        self::PARAM_ID,
        self::PARAM_CLASS,
        self::DEFAULT_CSS_CLASSES,
        self::BUTTON_CLASS,
        self::ICON,
    ];

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

    /**
     * @return string
     */
    public function generate()
    {
        $html = $this->generateAnchor();
        $html .= $this->options[self::ICON];
        $html .= $this->title;
        $html .= '</a>';

        return $html;
    }

    /**
     * @return string
     */
    protected function getId()
    {
        if (array_key_exists(self::PARAM_ID, $this->options)) {
            return ' id="' . $this->options[self::PARAM_ID] . '"';
        }

        return '';
    }

    /**
     * @return string
     */
    protected function getClass()
    {
        return ' class="' . $this->getButtonClasses() . '"';
    }

    /**
     * @return string
     */
    protected function getButtonClasses()
    {
        $class = [
            'btn',
            $this->options[self::DEFAULT_CSS_CLASSES],
            $this->options[self::BUTTON_CLASS],
        ];

        if (array_key_exists(self::PARAM_CLASS, $this->options)) {
            $class[] = $this->options[self::PARAM_CLASS];
        }

        return implode(' ', $class);
    }

    /**
     * @return string
     */
    protected function getExtraAttributes()
    {
        $extraAttributes = array_diff_key($this->options, array_flip(static::CUSTOM_OPTIONS));

        if (empty($extraAttributes)) {
            return '';
        }

        $html = '';
        foreach ($extraAttributes as $htmlAttributeName => $attributeValue) {
            $html .= sprintf(' %s="%s"', $htmlAttributeName, $attributeValue);
        }

        return $html;
    }

    /**
     * @return string
     */
    protected function generateAnchor()
    {
        return '<a' . $this->getClass() . $this->getId() . $this->getExtraAttributes() . ' href="' . $this->url . '">';
    }
}
