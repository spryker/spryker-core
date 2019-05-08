<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ContentGuiConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getEditorContentWidgetHtml(): string
    {
        return '<div contenteditable="false" class="content-item-block" data-type="%TYPE%" data-id="%ID%" data-template="%TEMPLATE%" data-twig-function="%TWIG_FUNCTION%">
                    <p>Content Item Type: <b>%TYPE%</b></p>
                    <p>Content Item ID#: <b>%ID%</b></p>
                    <p>Name: <b>%NAME%</b></p>
                    <p>Template: <b>%TEMPLATE_DISPLAY_NAME%</b></p>
                </div>';
    }
}
