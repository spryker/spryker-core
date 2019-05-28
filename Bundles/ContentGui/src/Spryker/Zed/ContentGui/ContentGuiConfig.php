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
    protected const PARAMETER_TYPE = '%TYPE%';
    protected const PARAMETER_TEMPLATE = '%TEMPLATE%';
    protected const PARAMETER_TWIG_EXPRESSION = '%TWIG_EXPRESSION%';
    protected const PARAMETER_NAME = '%NAME%';
    protected const PARAMETER_TEMPLATE_DISPLAY_NAME = '%TEMPLATE_DISPLAY_NAME%';
    protected const EDITOR_CONTENT_WIDGET_WRAPPER = '<p>%s</p>';

    /**
     * @return string
     */
    public function getEditorContentWidgetTemplate(): string
    {
        return '<span class="content-item-editor js-content-item-editor" contenteditable="false" '
            . 'data-type="' . $this->getParameterType() . '" data-id="' . $this->getParameterId() . '" '
            . 'data-template="' . $this->getParameterTemplate() . '" data-twig-expression="' . $this->getParameterTwigExpression() . '">'
                . '<span>Content Item Type: <b>' . $this->getParameterType() . '</b></span>'
                . '<span>Content Item ID#: <b>' . $this->getParameterId() . '</b></span>'
                . '<span>Name: <b>' . $this->getParameterName() . '</b></span>'
                . '<span>Template: <b>' . $this->getParameterTemplateDisplayName() . '</b></span>'
            . '</span>';
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
    public function getParameterType(): string
    {
        return static::PARAMETER_TYPE;
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
}
