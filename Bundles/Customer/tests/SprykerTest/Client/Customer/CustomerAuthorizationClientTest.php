<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Customer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AuthorizationEntityTransfer;
use Generated\Shared\Transfer\AuthorizationIdentityTransfer;
use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Spryker\Client\Customer\Plugin\Authorization\CustomerReferenceMatchingEntityIdAuthorizationStrategyPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Customer
 * @group CustomerAuthorizationClientTest
 * Add your own group annotations below this line
 */
class CustomerAuthorizationClientTest extends Unit
{
    /**
     * @return void
     */
    public function testCustomerAuthorizationStrategyPlugin(): void
    {
        // Arrange
        $customerAuthorizationStrategyPlugin = new CustomerReferenceMatchingEntityIdAuthorizationStrategyPlugin();
        $authorizationRequestTransfer = (new AuthorizationRequestTransfer())
            ->setIdentity(
                (new AuthorizationIdentityTransfer())
                    ->setIdentifier('test'),
            )
            ->setEntity(
                (new AuthorizationEntityTransfer())
                    ->setIdentifier('test'),
            );

        // Act
        $result = $customerAuthorizationStrategyPlugin->authorize($authorizationRequestTransfer);

        // Assert
        $this->isTrue($result);
        $this->assertSame($customerAuthorizationStrategyPlugin->getStrategyName(), 'CustomerReferenceMatchingEntityId');
    }

    /**
     * @return void
     */
    public function testCustomerAuthorizationStrategyPluginReturnsFalse(): void
    {
        // Arrange
        $customerAuthorizationStrategyPlugin = new CustomerReferenceMatchingEntityIdAuthorizationStrategyPlugin();
        $authorizationRequestTransfer = (new AuthorizationRequestTransfer())
            ->setIdentity(
                (new AuthorizationIdentityTransfer())
                    ->setIdentifier('test'),
            )
            ->setEntity(
                (new AuthorizationEntityTransfer())
                    ->setIdentifier('wrongIdentifier'),
            );

        // Act
        $result = $customerAuthorizationStrategyPlugin->authorize($authorizationRequestTransfer);

        // Assert
        $this->isFalse($result);
        $this->assertSame($customerAuthorizationStrategyPlugin->getStrategyName(), 'CustomerReferenceMatchingEntityId');
    }
}
