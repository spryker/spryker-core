<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilder;

use Codeception\Test\Unit;
use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilder\TemplateNameBuilderZed;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Twig
 * @group Business
 * @group Model
 * @group TemplatePathMapBuilder
 * @group TemplateNameBuilder
 * @group TemplateNameBuilderZedTest
 * Add your own group annotations below this line
 */
class TemplateNameBuilderZedTest extends Unit
{
    /**
     * @dataProvider pathDataProvider
     *
     * @param string $path
     * @param string $expectedTemplateName
     *
     * @return void
     */
    public function testBuildTemplateName($path, $expectedTemplateName)
    {
        $templateNameBuilder = new TemplateNameBuilderZed();

        $this->assertSame($expectedTemplateName, $templateNameBuilder->buildTemplateName($path));
    }

    /**
     * @return array
     */
    public function pathDataProvider()
    {
        return [
            ['vendor/spryker/spryker/Modules/Module/src/Organization/Zed/Module/Presentation/Controller/index.twig', '@Module/Controller/index.twig'],
            ['vendor/spryker/bundle/src/Organization/Zed/Module/Presentation/Controller/index.twig', '@Module/Controller/index.twig'],
            ['vendor/spryker/bundle/src/Organization/Zed/Module/Presentation/Controller/SubDirectory/index.twig', '@Module/Controller/SubDirectory/index.twig'],
        ];
    }

    /**
     * @dataProvider namespacedPathDataProvider
     *
     * @param string $path
     * @param string $expectedTemplateName
     *
     * @return void
     */
    public function testBuildNamespacedTemplateName($path, $expectedTemplateName)
    {
        $templateNameBuilder = new TemplateNameBuilderZed();

        $this->assertSame($expectedTemplateName, $templateNameBuilder->buildNamespacedTemplateName($path));
    }

    /**
     * @return array
     */
    public function namespacedPathDataProvider()
    {
        return [
            ['vendor/spryker/spryker/Modules/Module/src/Organization/Zed/Module/Presentation/Controller/index.twig', '@Organization:Module/Controller/index.twig'],
            ['vendor/spryker/bundle/src/Organization/Zed/Module/Presentation/Controller/index.twig', '@Organization:Module/Controller/index.twig'],
            ['vendor/spryker/bundle/src/Organization/Zed/Module/Presentation/Controller/SubDirectory/index.twig', '@Organization:Module/Controller/SubDirectory/index.twig'],
        ];
    }
}
