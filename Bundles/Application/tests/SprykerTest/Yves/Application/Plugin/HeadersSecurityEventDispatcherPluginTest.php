<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Application\Plugin;

use Codeception\Test\Unit;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Yves\Application\ApplicationDependencyProvider;
use Spryker\Yves\Application\Communication\Plugin\EventDispatcher\HeadersSecurityEventDispatcherPlugin;
use Spryker\Yves\ApplicationExtension\Dependency\Plugin\SecurityHeaderExpanderPluginInterface;
use SprykerTest\Yves\Application\ApplicationYvesTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Application
 * @group Plugin
 * @group HeadersSecurityEventDispatcherPluginTest
 * Add your own group annotations below this line
 */
class HeadersSecurityEventDispatcherPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Yves\Application\ApplicationYvesTester
     */
    protected ApplicationYvesTester $tester;

    /**
     * @return void
     */
    public function testExtendExecutesExpanderPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            ApplicationDependencyProvider::PLUGINS_SECURITY_HEADER_EXPANDER,
            [$this->getSecurityHeaderExpanderPluginMock()],
        );

        // Act
        (new HeadersSecurityEventDispatcherPlugin())->extend(
            $this->getMockBuilder(EventDispatcherInterface::class)->getMock(),
            $this->createMock(ContainerInterface::class),
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\ApplicationExtension\Dependency\Plugin\SecurityHeaderExpanderPluginInterface
     */
    protected function getSecurityHeaderExpanderPluginMock(): SecurityHeaderExpanderPluginInterface
    {
        $securityHeaderExpanderPluginMock = $this
            ->getMockBuilder(SecurityHeaderExpanderPluginInterface::class)
            ->getMock();

        $securityHeaderExpanderPluginMock
            ->expects($this->once())
            ->method('expand')
            ->willReturnCallback(function (array $securityHeaders) {
                return $securityHeaders;
            });

        return $securityHeaderExpanderPluginMock;
    }
}
