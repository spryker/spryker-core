<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Twig\Business\Model\TemplatePathMapBuilder;

use Codeception\Test\Unit;
use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilderInterface;
use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplatePathMapBuilder;
use Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilderInterface;
use Symfony\Component\Finder\Finder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Twig
 * @group Business
 * @group Model
 * @group TemplatePathMapBuilder
 * @group TemplatePathMapBuilderTest
 * Add your own group annotations below this line
 */
class TemplatePathMapBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testCanBeInstantiated()
    {
        $templateNameBuilder = $this->getTemplateNameBuilderMock();
        $directory = $this->getFixtureDirectory();
        $templateFinder = new TemplatePathMapBuilder(new Finder(), $templateNameBuilder, $directory);

        $this->assertInstanceOf(TemplatePathMapBuilderInterface::class, $templateFinder);
    }

    /**
     * @return void
     */
    public function testBuildReturnsArray()
    {
        $templateNameBuilder = $this->getTemplateNameBuilderMock();
        $templateNameBuilder->expects($this->once())->method('buildTemplateName')->willReturn('@Bundle/Controller/index.twig');

        $directory = $this->getFixtureDirectory();
        $templateFinder = new TemplatePathMapBuilder(new Finder(), $templateNameBuilder, $directory);

        $this->assertIsArray($templateFinder->build());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Twig\Business\Model\TemplatePathMapBuilder\TemplateNameBuilderInterface
     */
    protected function getTemplateNameBuilderMock()
    {
        $mockBuilder = $this->getMockBuilder(TemplateNameBuilderInterface::class)
            ->setMethods(['buildTemplateName']);

        return $mockBuilder->getMock();
    }

    /**
     * @return string
     */
    protected function getFixtureDirectory()
    {
        return __DIR__ . '/Fixtures';
    }
}
