<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Twig\TemplateNameExtractor;

use Codeception\Test\Unit;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceBridge;
use Spryker\Yves\Twig\Model\TemplateNameExtractor\TemplateNameExtractor;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Twig
 * @group TemplateNameExtractor
 * @group TemplateNameExtractorTest
 * Add your own group annotations below this line
 */
class TemplateNameExtractorTest extends Unit
{
    /**
     * @dataProvider nameDataProviderForTemplatePath
     *
     * @param string $templateName
     * @param string $expectedBundleName
     *
     * @return void
     */
    public function testExtractTemplateNameShouldReturnTemplatePath($templateName, $expectedBundleName)
    {
        $templateNameExtractor = new TemplateNameExtractor($this->getUtilTextService());

        $this->assertSame($expectedBundleName, $templateNameExtractor->extractTemplatePath($templateName));
    }

    /**
     * @return array
     */
    public function nameDataProviderForTemplatePath()
    {
        return [
            ['@Bundle/DirectoryCamelCase/template.twig', 'directory-camel-case/template.twig'],
            ['@Bundle/Directory/template.twig', 'directory/template.twig'],
            ['@Bundle/directory/templateCamelCased.twig', 'directory/template-camel-cased.twig'],
        ];
    }

    /**
     * @return \Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceInterface
     */
    protected function getUtilTextService()
    {
        return new TwigToUtilTextServiceBridge(new UtilTextService());
    }
}
