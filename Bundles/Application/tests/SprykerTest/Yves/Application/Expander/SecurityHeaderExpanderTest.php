<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Application\Expander;

use Codeception\Test\Unit;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Yves\Application\ApplicationConfig;
use Spryker\Yves\Application\Expander\SecurityHeaderExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Application
 * @group Expander
 * @group SecurityHeaderExpanderTest
 * Add your own group annotations below this line
 */
class SecurityHeaderExpanderTest extends Unit
{
    /**
     * @var string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY = 'Content-Security-Policy';

    /**
     * @return void
     */
    public function testExpandReturnExpandedHeadersWhenRequestIsCorrect(): void
    {
        // Arrange
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $applicationConfigMock = $this->createMock(ApplicationConfig::class);
        $applicationConfigMock->method('getSecurityHeaders')->willReturn([
            static::HEADER_CONTENT_SECURITY_POLICY => 'form-action self',
        ]);
        $applicationConfigMock->method('getDomainWhitelist')->willReturn([
            'test.domain',
            'test.domain',
        ]);
        $securityHeaderExpanderMock = $this->getSecurityHeaderExpanderMock($applicationConfigMock);

        //Assert
        $securityHeaderExpanderMock->method('executeSecurityHeaderExpanderPlugins')
            ->with([
                static::HEADER_CONTENT_SECURITY_POLICY => 'form-action test.domain self',
            ]);

        //Act
        $securityHeaderExpanderMock->expand($eventDispatcherMock);
    }

    /**
     * @return void
     */
    public function testExpandReturnInitialHeadersWhenRequestDoesntHaveCSPHeader(): void
    {
        // Arrange
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $applicationConfigMock = $this->createMock(ApplicationConfig::class);
        $applicationConfigMock->method('getSecurityHeaders')->willReturn([
            'test' => 'test',
        ]);
        $securityHeaderExpanderMock = $this->getSecurityHeaderExpanderMock($applicationConfigMock);

        //Assert
        $securityHeaderExpanderMock->method('executeSecurityHeaderExpanderPlugins')
            ->with([
                'test' => 'test',
            ]);

        //Act
        $securityHeaderExpanderMock->expand($eventDispatcherMock);
    }

    /**
     * @return void
     */
    public function testExpandReturnInitialHeadersWhenRequestDoesntHaveWhitelistedDomains(): void
    {
        // Arrange
        $eventDispatcherMock = $this->createMock(EventDispatcherInterface::class);
        $applicationConfigMock = $this->createMock(ApplicationConfig::class);
        $applicationConfigMock->method('getSecurityHeaders')->willReturn([
            static::HEADER_CONTENT_SECURITY_POLICY => 'form-action self',
        ]);
        $securityHeaderExpanderMock = $this->getSecurityHeaderExpanderMock($applicationConfigMock);

        //Assert
        $securityHeaderExpanderMock->method('executeSecurityHeaderExpanderPlugins')
            ->with([
                static::HEADER_CONTENT_SECURITY_POLICY => 'form-action self',
            ]);

        //Act
        $securityHeaderExpanderMock->expand($eventDispatcherMock);
    }

    /**
     * @param \Spryker\Yves\Application\ApplicationConfig $applicationConfigMock
     *
     * @return \Spryker\Yves\Application\Expander\SecurityHeaderExpander|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getSecurityHeaderExpanderMock(ApplicationConfig $applicationConfigMock): SecurityHeaderExpander
    {
        return $this->getMockBuilder(SecurityHeaderExpander::class)
            ->setConstructorArgs([$applicationConfigMock, []])
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['executeSecurityHeaderExpanderPlugins'])
            ->getMock();
    }
}
