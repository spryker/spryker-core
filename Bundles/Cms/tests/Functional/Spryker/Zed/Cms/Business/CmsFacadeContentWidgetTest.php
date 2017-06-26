<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Cms\Business;

use Codeception\TestCase\Test;
use Pyz\Zed\Cms\Business\CmsFacade;
use Spryker\Shared\Cms\CmsContentWidget\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Zed\Cms\Business\CmsBusinessFactory;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface;
use Spryker\Zed\Kernel\Container;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Cms
 * @group Business
 * @group CmsFacadeContentWidgetTest
 */
class CmsFacadeContentWidgetTest extends Test
{

    /**
     * @var \Spryker\Zed\Cms\Business\CmsFacade
     */
    protected $cmsFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cmsFacade = new CmsFacade();
    }

    /**
     * @return void
     */
    public function testMapContentWidgetParametersShouldSkipMappingIfThereIsNoTwigContent()
    {
        $parameterMap = $this->cmsFacade->mapContentWidgetParameters('cms content without twig functions');
        $this->assertEmpty($parameterMap);
    }

    /**
     * @return void
     */
    public function testMapContentWidgetParametersShouldMapParametersWithPlugin()
    {
        $mockedCmsContentWidgetFunction = $this->createMockedCmsContentWidgetFunction();

        $mockedCmsContentWidgetFunction->method('map')->willReturn([
            'sku1' => 1,
            'sku2' => 2,
        ]);

        $cmsFacade = $this->createCmsFacadeWithMockedContentWidgetParameterMapper($mockedCmsContentWidgetFunction);

        $parameterMap = $cmsFacade->mapContentWidgetParameters("cms content {{ function(['sku1', 'sku2']) }} twig functions.");

        $this->assertArrayHasKey('function', $parameterMap);
        $this->assertCount(2,  $parameterMap['function']);
    }

    /**
     * @dataProvider getContentWidgetDataProvider
     *
     * @param string $functionName
     * @param array $availableTemplates
     * @param string $usageInformation
     *
     * @return void
     */
    public function testGetContentWidgetConfigurationListShouldReturnProvidedConfigurations(
        $functionName,
        array $availableTemplates,
        $usageInformation
    ) {
        $cmsContentWidgetConfigurationProviderMock = $this->createCmsContentWidgetConfigurationProviderMock();

        $cmsContentWidgetConfigurationProviderMock
            ->expects($this->once())
            ->method('getFunctionName')
            ->willReturn($functionName);

        $cmsContentWidgetConfigurationProviderMock
            ->expects($this->once())
            ->method('getAvailableTemplates')
            ->willReturn($availableTemplates);

        $cmsContentWidgetConfigurationProviderMock
            ->expects($this->once())
            ->method('getUsageInformation')
            ->willReturn($usageInformation);

        $cmsFacade = $this->createCmsFacadeWithMockedContentWidgetConfigurationProviders($cmsContentWidgetConfigurationProviderMock);

        $cmsContentWidgetConfigurationListTransfer = $cmsFacade->getContentWidgetConfigurationList();
        $this->assertCount(1, $cmsContentWidgetConfigurationListTransfer->getCmsContentWidgetConfigurationList());

        $cmsContentWidgetConfigurationTransfer = $cmsContentWidgetConfigurationListTransfer->getCmsContentWidgetConfigurationList()[0];

        $this->assertEquals($functionName, $cmsContentWidgetConfigurationTransfer->getFunctionName());
        $this->assertCount(count($availableTemplates), $cmsContentWidgetConfigurationTransfer->getTemplates());

        $mappedTemplates = $cmsContentWidgetConfigurationTransfer->getTemplates();
        foreach ($availableTemplates as $identifier => $templatePath) {
            $this->assertArrayHasKey($identifier, $mappedTemplates);
            $this->assertEquals($mappedTemplates[$identifier], $templatePath);
        }

        $this->assertEquals($usageInformation, $cmsContentWidgetConfigurationTransfer->getUsageInformation());
    }

    /**
     * @return array
     */
    public function getContentWidgetDataProvider()
    {
        return [
            [
               'getFunctionName' => 'functionName',
               'getAvailableTemplates' => [
                   'identifier' => '@module/path/to/template.twig',
               ],
               'getUsageInformation' => 'how to..',
            ],
            [
                'getFunctionName' => 'functionName1',
                'getAvailableTemplates' => [
                    'identifier1' => '@module/path/to/template1.twig',
                    'identifier2' => '@module/path/to/template2.twig',
                ],
                'getUsageInformation' => 'how to..2',
            ],
        ];
    }

    /**
     * @param \Spryker\Shared\Cms\CmsContentWidget\CmsContentWidgetConfigurationProviderInterface $cmsContentWidgetConfigurationProviderMock
     *
     * @return \Pyz\Zed\Cms\Business\CmsFacade
     */
    protected function createCmsFacadeWithMockedContentWidgetConfigurationProviders(
        CmsContentWidgetConfigurationProviderInterface $cmsContentWidgetConfigurationProviderMock
    ) {
        $cmsFacade = $this->createCmsFacade();
        $cmsBusinessFactory = $this->createBusinessFactory();

        $cmsConfigMock = $this->createCmsConfigMock();
        $cmsConfigMock->method('getCmsContentWidgetConfigurationProviders')->willReturn([
            'function' => $cmsContentWidgetConfigurationProviderMock,
        ]);

        $cmsBusinessFactory->setConfig($cmsConfigMock);
        $cmsFacade->setFactory($cmsBusinessFactory);

        return $cmsFacade;
    }

    /**
     * @param \Spryker\Zed\Cms\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface $cmsContentWidgetParameterMapperPluginMock
     *
     * @return \Pyz\Zed\Cms\Business\CmsFacade
     */
    protected function createCmsFacadeWithMockedContentWidgetParameterMapper(
        CmsContentWidgetParameterMapperPluginInterface $cmsContentWidgetParameterMapperPluginMock
    ) {

        $cmsFacade = $this->createCmsFacade();
        $cmsBusinessFactory = $this->createBusinessFactory();

        $container = $this->createZedContainer();
        $container[CmsDependencyProvider::PLUGINS_CMS_CONTENT_WIDGET_PARAMETER_MAPPERS] = function (Container $container) use ($cmsContentWidgetParameterMapperPluginMock) {
            return [
              'function' => $cmsContentWidgetParameterMapperPluginMock,
            ];
        };

        $cmsBusinessFactory->setContainer($container);

        $cmsFacade->setFactory($cmsBusinessFactory);

        return $cmsFacade;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface
     */
    protected function createMockedCmsContentWidgetFunction()
    {
        return $this->getMockBuilder(CmsContentWidgetParameterMapperPluginInterface::class)
            ->getMock();
    }

    /**
     * @return \Pyz\Zed\Cms\Business\CmsFacade
     */
    protected function createCmsFacade()
    {
        return new CmsFacade();
    }

    /**
     * @return \Spryker\Zed\Cms\Business\CmsBusinessFactory
     */
    protected function createBusinessFactory()
    {
        return new CmsBusinessFactory();
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function createZedContainer()
    {
        return new Container();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\CmsConfig
     */
    protected function createCmsConfigMock()
    {
        return $this->getMockBuilder(CmsConfig::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\Cms\CmsContentWidget\CmsContentWidgetConfigurationProviderInterface
     */
    protected function createCmsContentWidgetConfigurationProviderMock()
    {
        return $this->getMockBuilder(CmsContentWidgetConfigurationProviderInterface::class)->getMock();
    }

}
