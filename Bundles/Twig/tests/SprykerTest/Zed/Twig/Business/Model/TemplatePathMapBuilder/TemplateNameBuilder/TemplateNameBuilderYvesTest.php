<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilder;

use Codeception\Test\Unit;
use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilder\TemplateNameBuilderYves;

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
 * @group TemplateNameBuilderYvesTest
 * Add your own group annotations below this line
 */
class TemplateNameBuilderYvesTest extends Unit
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
        $templateNameBuilder = new TemplateNameBuilderYves();

        $this->assertSame($expectedTemplateName, $templateNameBuilder->buildTemplateName($path));
    }

    /**
     * @return array
     */
    public function pathDataProvider()
    {
        return [
            ['src/Organization/Yves/Module/Theme/theme-name/Controller/index.twig', '@Module/Controller/index.twig'],
            ['vendor/spryker/spryker/Modules/Module/src/Organization/Yves/Module/Theme/theme-name/Controller/index.twig', '@Module/Controller/index.twig'],
            ['vendor/spryker/Module/src/Organization/Yves/Module/Theme/theme-name/Controller/index.twig', '@Module/Controller/index.twig'],
            ['vendor/spryker/Module/src/Organization/Yves/Module/Theme/theme-name/Controller/SubDirectory/index.twig', '@Module/Controller/SubDirectory/index.twig'],
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
        $templateNameBuilder = new TemplateNameBuilderYves();

        $this->assertSame($expectedTemplateName, $templateNameBuilder->buildNamespacedTemplateName($path));
    }

    /**
     * @return array
     */
    public function namespacedPathDataProvider()
    {
        return [
            ['src/Organization/Yves/Module/Theme/theme-name/Controller/index.twig', '@Organization:Module/Controller/index.twig'],
            ['vendor/spryker/spryker/Modules/Module/src/Organization/Yves/Module/Theme/theme-name/Controller/index.twig', '@Organization:Module/Controller/index.twig'],
            ['vendor/spryker/Module/src/Organization/Yves/Module/Theme/theme-name/Controller/index.twig', '@Organization:Module/Controller/index.twig'],
            ['vendor/spryker/Module/src/Organization/Yves/Module/Theme/theme-name/Controller/SubDirectory/index.twig', '@Organization:Module/Controller/SubDirectory/index.twig'],
        ];
    }
}
