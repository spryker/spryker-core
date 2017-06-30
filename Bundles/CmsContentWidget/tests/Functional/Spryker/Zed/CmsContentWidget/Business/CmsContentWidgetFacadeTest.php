<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\CmsContentWidget\Business;

use Codeception\TestCase\Test;
use Spryker\Shared\CmsContentWidget\CmsContentWidget\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetBusinessFactory;
use Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacade;
use Spryker\Zed\CmsContentWidget\CmsContentWidgetConfig;
use Spryker\Zed\CmsContentWidget\CmsContentWidgetDependencyProvider;
use Spryker\Zed\CmsContentWidget\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface;
use Spryker\Zed\Kernel\Container;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group CmsContentWidget
 * @group Business
 * @group CmsContentWidgetFacadeTest
 */
class CmsContentWidgetFacadeTest extends Test
{

    /**
     * @var \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacade
     */
    protected $cmsContentWidgetFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cmsContentWidgetFacade = new CmsContentWidgetFacade();
    }

    /**
     * @return void
     */
    public function testMapContentWidgetParametersShouldSkipMappingIfThereIsNoTwigContent()
    {
        $parameterMap = $this->cmsContentWidgetFacade->mapContentWidgetParameters('cms content without twig functions');
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
     * @param \Spryker\Shared\CmsContentWidget\CmsContentWidget\CmsContentWidgetConfigurationProviderInterface $cmsContentWidgetConfigurationProviderMock
     *
     * @return \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacade
     */
    protected function createCmsFacadeWithMockedContentWidgetConfigurationProviders(
        CmsContentWidgetConfigurationProviderInterface $cmsContentWidgetConfigurationProviderMock
    ) {
        $cmsContentWidgetFacade = $this->createCmsContentWidgetFacade();
        $cmsBusinessFactory = $this->createBusinessFactory();

        $cmsConfigMock = $this->createCmsConfigMock();
        $cmsConfigMock->method('getCmsContentWidgetConfigurationProviders')->willReturn([
            'function' => $cmsContentWidgetConfigurationProviderMock,
        ]);

        $cmsBusinessFactory->setConfig($cmsConfigMock);
        $cmsContentWidgetFacade->setFactory($cmsBusinessFactory);

        return $cmsContentWidgetFacade;
    }

    /**
     * @param \Spryker\Zed\CmsContentWidget\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface $cmsContentWidgetParameterMapperPluginMock
     *
     * @return \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacade
     */
    protected function createCmsFacadeWithMockedContentWidgetParameterMapper(
        CmsContentWidgetParameterMapperPluginInterface $cmsContentWidgetParameterMapperPluginMock
    ) {

        $cmsContentFacade = $this->createCmsContentWidgetFacade();
        $cmsBusinessFactory = $this->createBusinessFactory();

        $container = $this->createZedContainer();
        $container[CmsContentWidgetDependencyProvider::PLUGINS_CMS_CONTENT_WIDGET_PARAMETER_MAPPERS] = function (Container $container) use ($cmsContentWidgetParameterMapperPluginMock) {
            return [
              'function' => $cmsContentWidgetParameterMapperPluginMock,
            ];
        };

        $cmsBusinessFactory->setContainer($container);

        $cmsContentFacade->setFactory($cmsBusinessFactory);

        return $cmsContentFacade;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\CmsContentWidget\Dependency\Plugin\CmsContentWidgetParameterMapperPluginInterface
     */
    protected function createMockedCmsContentWidgetFunction()
    {
        return $this->getMockBuilder(CmsContentWidgetParameterMapperPluginInterface::class)
            ->getMock();
    }

    /**
     * @return \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetFacade
     */
    protected function createCmsContentWidgetFacade()
    {
        return new CmsContentWidgetFacade();
    }

    /**
     * @return \Spryker\Zed\CmsContentWidget\Business\CmsContentWidgetBusinessFactory
     */
    protected function createBusinessFactory()
    {
        return new CmsContentWidgetBusinessFactory();
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function createZedContainer()
    {
        return new Container();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\CmsContentWidget\CmsContentWidgetConfig
     */
    protected function createCmsConfigMock()
    {
        return $this->getMockBuilder(CmsContentWidgetConfig::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Shared\CmsContentWidget\CmsContentWidget\CmsContentWidgetConfigurationProviderInterface
     */
    protected function createCmsContentWidgetConfigurationProviderMock()
    {
        return $this->getMockBuilder(CmsContentWidgetConfigurationProviderInterface::class)->getMock();
    }

}
