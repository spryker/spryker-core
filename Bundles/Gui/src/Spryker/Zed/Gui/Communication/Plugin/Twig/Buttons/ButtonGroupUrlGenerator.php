<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons;

class ButtonGroupUrlGenerator implements UrlGeneratorInterface
{
    public const BUTTON_CLASS = 'class';
    public const ICON = 'icon';

    /**
     * @var array
     */
    protected $buttons;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param array $buttons
     * @param string $title
     * @param array $options
     */
    public function __construct(array $buttons, $title, array $options)
    {
        $this->buttons = $buttons;
        $this->title = $title;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $class = $this->getClass();
        $icon = $this->getIcon();
        $optionParams = $this->getOptionsParam();

        $html = sprintf(
            '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-sm btn-outline %s dropdown-toggle" aria-expanded="true" %s>%s%s <span class="caret"></span></button>',
            $class,
            $optionParams,
            $icon,
            $this->title
        );

        $html .= '<ul class="dropdown-menu">';
        foreach ($this->buttons as $button) {
            $html .= $this->generateAnchor($button['url']);
            $html .= $button['title'];
            $html .= '</a></li>';
        }

        $html .= '</ul></div>';

        return $html;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function generateAnchor($url)
    {
        return '<li><a href="' . $url . '">';
    }

    /**
     * @return string
     */
    protected function getClass()
    {
        if (!isset($this->options['class'])) {
            return '';
        }

        return $this->options['class'];
    }

    /**
     * @return string
     */
    protected function getIcon()
    {
        if (!isset($this->options['icon'])) {
            return '';
        }

        return $this->options['icon'];
    }

    /**
     * @return string
     */
    protected function getOptionsParam()
    {
        if (!isset($this->options['options'])) {
            return '';
        }

        return $this->options['options'];
    }
}
