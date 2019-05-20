<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\BusinessOnBehalf;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\BusinessOnBehalf\Checker\CompanyUserChecker;
use Spryker\Client\BusinessOnBehalfExtension\Dependency\Plugin\CompanyUserChangeAllowedCheckPluginInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group BusinessOnBehalf
 * @group CompanyUserCheckerTest
 * Add your own group annotations below this line
 */
class CompanyUserCheckerTest extends Unit
{
    /**
     * @return void
     */
    public function testIsCompanyUserChangeAllowedReturnsTrueByDefault(): void
    {
        // Arrange
        $companyUserCheckerMock = $this->createCompanyUserCheckerMock();

        // Act
        $isAllowed = $companyUserCheckerMock->isCompanyUserChangeAllowed(new CustomerTransfer());

        // Assert
        $this->assertTrue($isAllowed);
    }

    /**
     * @return void
     */
    public function testIsCompanyUserChangeAllowedWhenPluginStackReturnFalse(): void
    {
        // Arrange
        $companyUserChangeAllowedCheckPluginMock = $this->getMockBuilder(CompanyUserChangeAllowedCheckPluginInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['check'])
            ->getMock();

        $companyUserChangeAllowedCheckPluginMock->method('check')
            ->willReturn(false);

        $companyUserCheckerMock = $this->createCompanyUserCheckerMock([$companyUserChangeAllowedCheckPluginMock]);

        // Act
        $isAllowed = $companyUserCheckerMock->isCompanyUserChangeAllowed(new CustomerTransfer());

        // Assert
        $this->assertFalse($isAllowed);
    }

    /**
     * @param \Spryker\Client\BusinessOnBehalfExtension\Dependency\Plugin\CompanyUserChangeAllowedCheckPluginInterface[] $companyUserChangeAllowedCheckPlugins
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\BusinessOnBehalf\Checker\CompanyUserChecker
     */
    protected function createCompanyUserCheckerMock(array $companyUserChangeAllowedCheckPlugins = []): MockObject
    {
        return $this->getMockBuilder(CompanyUserChecker::class)
            ->setConstructorArgs([
                $companyUserChangeAllowedCheckPlugins,
            ])
            ->setMethods(null)
            ->getMock();
    }
}
