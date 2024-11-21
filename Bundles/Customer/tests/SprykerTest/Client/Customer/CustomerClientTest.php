<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Customer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\CustomerClient;
use Spryker\Client\Customer\Session\CustomerSession;
use Spryker\Shared\Customer\CustomerConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Customer
 * @group CustomerClientTest
 * Add your own group annotations below this line
 */
class CustomerClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\Customer\CustomerClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetUserIdentifierReturnsTheAnonymousIdFromTheSession(): void
    {
        // Arrange
        $this->tester->mockFactoryMethod(
            'createSessionCustomerSession',
            new CustomerSession(
                $this->tester->getSessionClientMock([CustomerConfig::ANONYMOUS_SESSION_KEY => 'anonymous:123']),
            ),
        );

        $customerClient = new CustomerClient();
        $customerClient->setFactory($this->tester->getFactory());

        // Act
        $userIdentifier = $customerClient->getUserIdentifier();

        // Assert
        $this->assertSame('anonymous:123', $userIdentifier);
    }

    /**
     * @return void
     */
    public function testGetUserIdentifierReturnsTheCustomerReferenceFromTheSession(): void
    {
        // Arrange
        $this->tester->mockFactoryMethod(
            'createSessionCustomerSession',
            new CustomerSession(
                $this->tester->getSessionClientMock([CustomerSession::SESSION_KEY => (new CustomerTransfer())->setCustomerReference('registered:123')]),
            ),
        );

        $customerClient = new CustomerClient();
        $customerClient->setFactory($this->tester->getFactory());

        // Act
        $userIdentifier = $customerClient->getUserIdentifier();

        // Assert
        $this->assertSame('registered:123', $userIdentifier);
    }
}
