<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ContentGuiConfig extends AbstractBundleConfig
{
    protected const PARAMETER_ID = '%ID%';
    protected const PARAMETER_KEY = '%KEY%';
    protected const PARAMETER_TYPE = '%TYPE%';
    protected const PARAMETER_DISPLAY_TYPE = '%DISPLAY_TYPE%';
    protected const PARAMETER_TEMPLATE = '%TEMPLATE%';
    protected const PARAMETER_TWIG_EXPRESSION = '%TWIG_EXPRESSION%';
    protected const PARAMETER_NAME = '%NAME%';
    protected const PARAMETER_TEMPLATE_DISPLAY_NAME = '%TEMPLATE_DISPLAY_NAME%';
    protected const EDITOR_CONTENT_WIDGET_WRAPPER = '<p>%s</p>';
    protected const MAX_WIDGET_NUMBER = 10000;
    protected const DOM_PATH_WIDGET_QUERY = '//span[@contenteditable="false"][@data-key][@data-twig-expression][@data-template][@data-type]';

    /**
     * @return string
     */
    public function getEditorContentWidgetTemplate(): string
    {
        return '<span class="content-item-editor js-content-item-editor" contenteditable="false" '
            . 'data-type="' . $this->getParameterType() . '" data-key="' . $this->getParameterKey() . '" '
            . 'data-display-type="' . $this->getParameterDisplayType() . '" '
            . 'data-id="' . $this->getParameterId() . '" '
            . 'data-template="' . $this->getParameterTemplate() . '" data-twig-expression="' . $this->getParameterTwigExpression() . '">'
                . '<span>Content Item Type: <b>' . $this->getParameterDisplayType() . '</b></span>'
                . '<span>Content Item Key#: <b>' . $this->getParameterKey() . '</b></span>'
                . '<span>Name: <b>' . $this->getParameterName() . '</b></span>'
                . '<span>Template: <b>' . $this->getParameterTemplateDisplayName() . '</b></span>'
            . '</span>';
    }

    /**
     * @return string
     */
    public function getWidgetXpathQuery(): string
    {
        return static::DOM_PATH_WIDGET_QUERY;
    }

    /**
     * @return string
     */
    public function getParameterId(): string
    {
        return static::PARAMETER_ID;
    }

    /**
     * @return string
     */
    public function getParameterKey(): string
    {
        return static::PARAMETER_KEY;
    }

    /**
     * @return string
     */
    public function getParameterType(): string
    {
        return static::PARAMETER_TYPE;
    }

    /**
     * @return string
     */
    public function getParameterDisplayType(): string
    {
        return static::PARAMETER_DISPLAY_TYPE;
    }

    /**
     * @return string
     */
    public function getParameterTemplate(): string
    {
        return static::PARAMETER_TEMPLATE;
    }

    /**
     * @return string
     */
    public function getParameterTwigExpression(): string
    {
        return static::PARAMETER_TWIG_EXPRESSION;
    }

    /**
     * @return string
     */
    public function getParameterName(): string
    {
        return static::PARAMETER_NAME;
    }

    /**
     * @return string
     */
    public function getParameterTemplateDisplayName(): string
    {
        return static::PARAMETER_TEMPLATE_DISPLAY_NAME;
    }

    /**
     * @return string
     */
    public function getEditorContentWidgetWrapper(): string
    {
        return static::EDITOR_CONTENT_WIDGET_WRAPPER;
    }

    /**
     * @return int
     */
    public function getMaxWidgetNumber(): int
    {
        return static::MAX_WIDGET_NUMBER;
    }
}
