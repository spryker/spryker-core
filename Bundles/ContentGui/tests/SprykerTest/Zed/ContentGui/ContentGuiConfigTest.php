<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentGui;

use Spryker\Zed\ContentGui\ContentGuiConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ContentGui
 * @group ContentGuiConfigTest
 * Add your own group annotations below this line
 */
class ContentGuiConfigTest extends ContentGuiConfig
{
    /**
     * @return string
     */
    public function getEditorContentWidgetWrapper(): string
    {
        return '%s';
    }

    /**
     * @return string
     */
    public function getEditorContentWidgetTemplate(): string
    {
        return '<span data-type="' . $this->getParameterType() . '" data-key="' . $this->getParameterKey() . '" '
        . 'data-template="' . $this->getParameterTemplate() . '" data-twig-expression="' . $this->getParameterTwigExpression() . '">'
        . '<span>Content Item Type: ' . $this->getParameterDisplayType() . '</span>'
        . '<span>Name: ' . $this->getParameterName() . '</span>'
        . '<span>Template: ' . $this->getParameterTemplateDisplayName() . '</span>'
        . '</span>';
    }

    /**
     * @return string
     */
    public function getWidgetXpathQuery(): string
    {
        return '//span[@data-key][@data-twig-expression][@data-template][@data-type]';
    }
}
