<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Yves\Twig\TemplateNameExtractor;

use PHPUnit_Framework_TestCase;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Shared\Twig\Dependency\Service\TwigToUtilTextServiceBridge;
use Spryker\Yves\Twig\Model\TemplateNameExtractor\TemplateNameExtractor;

/**
 * @group Functional
 * @group Spryker
 * @group Yves
 * @group Twig
 * @group TemplateNameExtractor
 * @group TemplateNameExtractorTest
 */
class TemplateNameExtractorTest extends PHPUnit_Framework_TestCase
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
