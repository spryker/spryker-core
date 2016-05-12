<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Tables;

use Spryker\Shared\Url\Url;
use Spryker\Zed\Library\Sanitize\Html;

class ButtonGenerator
{

    const BUTTON_CLASS = 'class';
    const BUTTON_HREF = 'href';
    const BUTTON_DEFAULT_CLASS = 'btn-default';
    const BUTTON_ICON = 'icon';
    const BUTTON_SCOPE_CLASS = 'btn btn-xs btn-outline';

    /**
     * @param string|\Spryker\Shared\Url\Url $url
     * @param string $title
     * @param array $defaultOptions
     * @param array $customOptions
     *
     * @return string
     */
    public function generateButton($url, $title, array $defaultOptions, array $customOptions = [])
    {
        $buttonOptions = $this->generateButtonOptions($defaultOptions, $customOptions);

        $class = $this->getButtonClass($defaultOptions, $customOptions);
        $parameters = $this->getButtonParameters($buttonOptions);

        $url = $this->sanitizeUrl($url);

        $html = '<a href="' . $url . '" class="' . self::BUTTON_SCOPE_CLASS . ' ' . $class . '"' . $parameters . '>';

        if (array_key_exists(self::BUTTON_ICON, $buttonOptions) === true && $buttonOptions[self::BUTTON_ICON] !== null) {
            $html .= '<i class="fa ' . $buttonOptions[self::BUTTON_ICON] . '"></i> ';
        }

        $html .= $title;
        $html .= '</a>';

        return $html;
    }

    /**
     * @param array $defaultOptions
     * @param array $options
     *
     * @return array
     */
    protected function generateButtonOptions(array $defaultOptions, array $options = [])
    {
        $buttonOptions = $defaultOptions;
        if (is_array($options)) {
            $buttonOptions = array_merge($defaultOptions, $options);
        }

        return $buttonOptions;
    }

    /**
     * @param array $defaultOptions
     * @param array $options
     *
     * @return string
     */
    protected function getButtonClass(array $defaultOptions, array $options = [])
    {
        $class = '';

        if (isset($defaultOptions[self::BUTTON_CLASS])) {
            $class .= ' ' . $defaultOptions[self::BUTTON_CLASS];
        }
        if (isset($options[self::BUTTON_CLASS])) {
            $class .= ' ' . $options[self::BUTTON_CLASS];
        }

        if (empty($class)) {
            return self::BUTTON_DEFAULT_CLASS;
        }

        return $class;
    }

    /**
     * @param array $buttonOptions
     *
     * @return string
     */
    protected function getButtonParameters(array $buttonOptions)
    {
        $parameters = '';
        foreach ($buttonOptions as $argument => $value) {
            if (in_array($argument, [self::BUTTON_CLASS, self::BUTTON_HREF, self::BUTTON_ICON])) {
                continue;
            }
            $parameters .= sprintf(' %s=\'%s\'', $argument, $value);
        }

        return $parameters;
    }

    /**
     * @param \Spryker\Shared\Url\Url|string $url
     *
     * @return string
     */
    protected function sanitizeUrl($url)
    {
        if ($url instanceof Url) {
            return $url->buildEscaped();
        }

        return Html::escape($url);
    }

}
