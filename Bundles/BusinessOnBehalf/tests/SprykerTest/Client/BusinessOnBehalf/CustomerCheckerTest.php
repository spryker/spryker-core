<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\BusinessOnBehalf;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\BusinessOnBehalf\Checker\CustomerChecker;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group BusinessOnBehalf
 * @group CustomerCheckerTest
 * Add your own group annotations below this line
 */
class CustomerCheckerTest extends Unit
{
    /**
     * @return void
     */
    public function testIsCustomerChangeAllowedReturnsTrueByDefault(): void
    {
        // Arrange
        $customerCheckerMock = $this->createCustomerCheckerMock();

        // Act
        $isAllowed = $customerCheckerMock->isCustomerChangeAllowed(new CustomerTransfer());

        // Assert
        $this->assertTrue($isAllowed);
    }

    /**
     * @param \Spryker\Client\BusinessOnBehalfExtension\Dependency\Plugin\CustomerChangeAllowedCheckPluginInterface[] $customerChangeAllowedCheckPlugins
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\BusinessOnBehalf\Checker\CustomerChecker
     */
    protected function createCustomerCheckerMock(array $customerChangeAllowedCheckPlugins = []): MockObject
    {
        return $this->getMockBuilder(CustomerChecker::class)
            ->setConstructorArgs([
                $customerChangeAllowedCheckPlugins,
            ])
            ->setMethods(null)
            ->getMock();
    }
}
