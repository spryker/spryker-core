<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Twig;

use Codeception\Test\Unit;
use Spryker\Service\Container\ContainerInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Twig\ProductServiceClassNameTwigPlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;
use Twig\Environment;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Twig
 * @group ProductServiceClassNameTwigPluginTest
 * Add your own group annotations below this line
 */
class ProductServiceClassNameTwigPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TWIG_GLOBAL_VARIABLE_PRODUCT_SERVICE_CLASS_NAME = 'serviceProductClassName';

    /**
     * @var string
     */
    protected const TEST_SERVICE_PRODUCT_CLASS_NAME = 'testServiceProductClassName';

    /**
     * @return void
     */
    public function testExtendAddsServiceProductClassNameGlobalVariable(): void
    {
        // Arrange
        $twigMock = $this->createMock(Environment::class);
        $containerMock = $this->createMock(ContainerInterface::class);

        $twigMock->expects($this->once())
            ->method('addGlobal')
            ->with(
                static::TWIG_GLOBAL_VARIABLE_PRODUCT_SERVICE_CLASS_NAME,
                static::TEST_SERVICE_PRODUCT_CLASS_NAME,
            );

        $configMock = $this->createConfigMock();
        $plugin = $this->createPluginWithConfig($configMock);

        // Act
        $result = $plugin->extend($twigMock, $containerMock);

        // Assert
        $this->assertSame($twigMock, $result);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig
     */
    protected function createConfigMock(): SelfServicePortalConfig
    {
        $configMock = $this->createMock(SelfServicePortalConfig::class);

        $configMock->method('getServiceProductClassName')
            ->willReturn(static::TEST_SERVICE_PRODUCT_CLASS_NAME);

        return $configMock;
    }

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $config
     *
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Twig\ProductServiceClassNameTwigPlugin
     */
    protected function createPluginWithConfig(SelfServicePortalConfig $config): ProductServiceClassNameTwigPlugin
    {
        $pluginMock = $this->getMockBuilder(ProductServiceClassNameTwigPlugin::class)
            ->onlyMethods(['getConfig'])
            ->getMock();

        $pluginMock->method('getConfig')
            ->willReturn($config);

        return $pluginMock;
    }
}
