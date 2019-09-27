<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Business\Template;

use Spryker\Zed\Cms\Business\Template\TemplatePlaceholderParser;
use Spryker\Zed\Cms\Business\Template\TemplatePlaceholderParserInterface;
use SprykerTest\Zed\Cms\Business\CmsMocks;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Cms
 * @group Business
 * @group Template
 * @group TemplatePlaceholderParserTest
 * Add your own group annotations below this line
 */
class TemplatePlaceholderParserTest extends CmsMocks
{
    /**
     * @return void
     */
    public function testGetTemplatePlaceholdersReturnsNoPlaceholders(): void
    {
        $templatePlaceholderParser = $this->createTemplatePlaceholderParser();
        $templateContent = '<!-- HTML comment {% block title %}test{% endblock %} -->';
        $placeholders = $templatePlaceholderParser->getTemplatePlaceholders($templateContent);
        $this->assertEmpty($placeholders);
    }

    /**
     * @return void
     */
    public function testGetTemplatePlaceholdersReturnsOnePlaceholder(): void
    {
        $templatePlaceholderParser = $this->createTemplatePlaceholderParser();
        $templateContent = '<!-- CMS_PLACEHOLDER : "title" -->';
        $placeholders = $templatePlaceholderParser->getTemplatePlaceholders($templateContent);
        $this->assertEquals(['title'], $placeholders);
    }

    /**
     * @return void
     */
    public function testGetTemplatePlaceholdersReturnsFewPlaceholder(): void
    {
        $templatePlaceholderParser = $this->createTemplatePlaceholderParser();
        $templateContent = '<!-- CMS_PLACEHOLDER : "title" -->' . PHP_EOL . '<!-- CMS_PLACEHOLDER : "content" -->';
        $placeholders = $templatePlaceholderParser->getTemplatePlaceholders($templateContent);
        $this->assertEquals(['title', 'content'], $placeholders);
    }

    /**
     * @return \Spryker\Zed\Cms\Business\Template\TemplatePlaceholderParserInterface
     */
    protected function createTemplatePlaceholderParser(): TemplatePlaceholderParserInterface
    {
        $cmsConfigMock = $this->createCmsConfigMock();
        $cmsConfigMock
            ->method('getPlaceholderPattern')
            ->willReturn('/<!-- CMS_PLACEHOLDER : "[a-zA-Z0-9._-]*" -->/');

        $cmsConfigMock
            ->method('getPlaceholderValuePattern')
            ->willReturn('/"([^"]+)"/');

        return new TemplatePlaceholderParser($cmsConfigMock);
    }
}
