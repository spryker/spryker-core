<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerAccessStorage\Communication\Plugin\Publisher;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Spryker\Zed\CustomerAccessStorage\Communication\Plugin\Publisher\CustomerAccessPublisherTriggerPlugin;
use SprykerTest\Zed\CustomerAccessStorage\CustomerAccessStorageCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerAccessStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group CustomerAccessPublisherTriggerPluginTest
 * Add your own group annotations below this line
 */
class CustomerAccessPublisherTriggerPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const EXPECTED_ID_UNAUTHENTICATED_CUSTOMER_ACCESS = 0;

    /**
     * @var \SprykerTest\Zed\CustomerAccessStorage\CustomerAccessStorageCommunicationTester
     */
    protected CustomerAccessStorageCommunicationTester $tester;

    /**
     * @return void
     */
    public function testGetDataShouldReturnAnArrayWithCustomerAccessTransferWhenOffsetIsEqualToZero(): void
    {
        // Arrange
        $customerAccessPublisherTriggerPlugin = new CustomerAccessPublisherTriggerPlugin();

        // Act
        $data = $customerAccessPublisherTriggerPlugin->getData(0, 0);

        // Assert
        $this->assertCount(1, $data);
        $this->assertInstanceOf(ContentTypeAccessTransfer::class, $data[0]);
        $this->assertSame(static::EXPECTED_ID_UNAUTHENTICATED_CUSTOMER_ACCESS, $data[0]->getIdUnauthenticatedCustomerAccess());
    }

    /**
     * @return void
     */
    public function testGetDataShouldReturnEmptyArrayWhenOffsetIsNotEqualToZero(): void
    {
        // Arrange
        $customerAccessPublisherTriggerPlugin = new CustomerAccessPublisherTriggerPlugin();

        // Act
        $data = $customerAccessPublisherTriggerPlugin->getData(1, 0);

        // Assert
        $this->assertSame([], $data);
    }
}
