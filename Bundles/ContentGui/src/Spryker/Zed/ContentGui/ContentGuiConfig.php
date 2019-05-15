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
    protected const PARAMETER_SHORT_CODE = '%SHORT_CODE%';
    protected const PARAMETER_NAME = '%NAME%';
    protected const PARAMETER_TEMPLATE_DISPLAY_NAME = '%TEMPLATE_DISPLAY_NAME%';

    /**
     * @return string
     */
    public function getEditorContentWidgetTemplate(): string
    {
        return '<div class="content-item-editor js-content-item-editor" contenteditable="false" '
                    . 'data-type="' . static::PARAMETER_TYPE . '" data-id="' . static::PARAMETER_ID . '" '
                    . 'data-template="' . static::PARAMETER_TEMPLATE . '" data-short-code="' . static::PARAMETER_SHORT_CODE . '">'
                        . '<p>Content Item Type: <b>' . static::PARAMETER_TYPE . '</b></p>'
                        . '<p>Content Item ID#: <b>' . static::PARAMETER_ID . '</b></p>'
                        . '<p>Name: <b>' . static::PARAMETER_NAME . '</b></p>'
                        . '<p>Template: <b>' . static::PARAMETER_TEMPLATE_DISPLAY_NAME . '</b></p>'
                . '</div>';
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
    public function getParameterShortCode(): string
    {
        return static::PARAMETER_SHORT_CODE;
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
}
