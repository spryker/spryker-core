<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Twig\TemplateNameExtractor;

use Codeception\Test\Unit;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceBridge;
use Spryker\Shared\Twig\TemplateNameExtractor\TemplateNameExtractor;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Twig
 * @group TemplateNameExtractor
 * @group TemplateNameExtractorTest
 * Add your own group annotations below this line
 */
class TemplateNameExtractorTest extends Unit
{
    /**
     * @dataProvider nameDataProviderForBundle
     *
     * @param string $templateName
     * @param string $expectedBundleName
     *
     * @return void
     */
    public function testExtractBundleNameShouldReturnBundleName($templateName, $expectedBundleName)
    {
        $templateNameExtractor = new TemplateNameExtractor($this->getUtilTextService());

        $this->assertSame($expectedBundleName, $templateNameExtractor->extractBundleName($templateName));
    }

    /**
     * @return array
     */
    public function nameDataProviderForBundle()
    {
        return [
            ['@Bundle/Directory/template.twig', 'Bundle'],
            ['/Bundle/Directory/template.twig', 'Bundle'],
            ['Bundle/Directory/template.twig', 'Bundle'],
            ['bundle/Directory/template.twig', 'Bundle'],
            ['bundle-name-dashed/Directory/template.twig', 'BundleNameDashed'],
        ];
    }

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
            ['@Bundle/directory/template.twig', 'Directory/template.twig'],
            ['@Bundle/Directory/template.twig', 'Directory/template.twig'],
            ['@Bundle/Directory/templateCamelCased.twig', 'Directory/template-camel-cased.twig'],
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
