<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ContentGuiConfig extends AbstractBundleConfig
{
    public const PARAMETER_ID = '%ID%';
    public const PARAMETER_TYPE = '%TYPE%';
    public const PARAMETER_TEMPLATE = '%TEMPLATE%';
    public const PARAMETER_SHORT_CODE = '%SHORT_CODE%';
    public const PARAMETER_NAME = '%NAME%';
    public const PARAMETER_TEMPLATE_DISPLAY_NAME = '%TEMPLATE_DISPLAY_NAME%';

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
}
