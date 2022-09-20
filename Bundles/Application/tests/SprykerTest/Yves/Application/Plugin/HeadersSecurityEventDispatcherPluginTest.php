<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Application\Plugin;

use Closure;
use Codeception\Test\Unit;
use ReflectionClass as ReflectionClassReflectionClass;
use ReflectionFunction;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Yves\Application\ApplicationDependencyProvider;
use Spryker\Yves\Application\Communication\Plugin\EventDispatcher\HeadersSecurityEventDispatcherPlugin;
use Spryker\Yves\ApplicationExtension\Dependency\Plugin\SecurityHeaderExpanderPluginInterface;
use SprykerTest\Yves\Application\ApplicationYvesTester;
use Symfony\Component\HttpKernel\KernelEvents;

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
     * @see {@link \Spryker\Yves\Application\ApplicationConfig::getSecurityHeaders()}
     *
     * @var string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY = 'Content-Security-Policy';

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
     * @dataProvider getContentSecurityPolicyHeaderFormActionTestData
     *
     * @param array<string, string> $securityHeaders
     * @param array<int, string> $domainWhitelist
     *
     * @return void
     */
    public function testContentSecurityPolicyHeaderFormActionValueIsExtendedWithWhitelistedDomainsIfPresent(
        array $securityHeaders,
        array $domainWhitelist
    ): void {
        // Arrange
        $this->tester->setDependency(
            ApplicationDependencyProvider::PLUGINS_SECURITY_HEADER_EXPANDER,
            [],
        );

        $this->tester->mockConfigMethod('getDomainWhitelist', $domainWhitelist);
        $applicationConfigMock = $this->tester->mockConfigMethod('getSecurityHeaders', $securityHeaders);

        $headersSecurityEventDispatcherPlugin = (new HeadersSecurityEventDispatcherPlugin());

        $headersSecurityEventDispatcherPluginReflection = new ReflectionClassReflectionClass($headersSecurityEventDispatcherPlugin);
        $getFactoryMethodReflection = $headersSecurityEventDispatcherPluginReflection->getMethod('getFactory');
        $getFactoryMethodReflection->setAccessible(true);

        $applicationFactory = $getFactoryMethodReflection->invoke($headersSecurityEventDispatcherPlugin);
        $applicationFactory->setConfig($applicationConfigMock);

        // Assert
        $eventDispatcherMock = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $eventDispatcherMock->expects($this->once())
            ->method('addListener')
            ->with(
                KernelEvents::RESPONSE,
                $this->callback(function (Closure $listenerCallback) use ($domainWhitelist, $securityHeaders) {
                    $listenerReflection = new ReflectionFunction($listenerCallback);
                    $headerContentSecurityPolicy = $listenerReflection->getStaticVariables()['securityHeaders'][static::HEADER_CONTENT_SECURITY_POLICY] ?? null;

                    if (!$headerContentSecurityPolicy) {
                        return empty($securityHeaders[static::HEADER_CONTENT_SECURITY_POLICY]);
                    }

                    if (!$domainWhitelist) {
                        return $headerContentSecurityPolicy === $securityHeaders[static::HEADER_CONTENT_SECURITY_POLICY];
                    }

                    foreach ($domainWhitelist as $whitelistedDomain) {
                        if (!mb_strstr($headerContentSecurityPolicy, $whitelistedDomain)) {
                            return false;
                        }
                    }

                    return true;
                }),
            );

        // Act
        $headersSecurityEventDispatcherPlugin->extend(
            $eventDispatcherMock,
            $this->createMock(ContainerInterface::class),
        );
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getContentSecurityPolicyHeaderFormActionTestData(): array
    {
        return [
            'all values are present' => [
                'securityHeaders' => [
                    static::HEADER_CONTENT_SECURITY_POLICY => 'base-uri \'self\'; form-action \'self\'',
                ],
                'domainWhitelist' => [
                    'yves.spryker.local',
                ],
            ],
            'domain whitelist is empty' => [
                'securityHeaders' => [
                    static::HEADER_CONTENT_SECURITY_POLICY => 'base-uri \'self\'; form-action \'self\'',
                ],
                'domainWhitelist' => [],
            ],
            'security header is empty' => [
                'securityHeaders' => [],
                'domainWhitelist' => [
                    'yves.spryker.local',
                ],
            ],
            'both values are empty' => [
                'securityHeaders' => [],
                'domainWhitelist' => [],
            ],
        ];
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
