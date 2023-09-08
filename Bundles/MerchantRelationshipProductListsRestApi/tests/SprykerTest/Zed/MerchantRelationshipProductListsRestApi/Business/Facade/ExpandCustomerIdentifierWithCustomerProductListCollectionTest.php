<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipProductListsRestApi\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\CustomerIdentifierBuilder;
use SprykerTest\Zed\MerchantRelationshipProductListsRestApi\MerchantRelationshipProductListsRestApiBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationshipProductListsRestApi
 * @group Business
 * @group Facade
 * @group ExpandCustomerIdentifierWithCustomerProductListCollectionTest
 * Add your own group annotations below this line
 */
class ExpandCustomerIdentifierWithCustomerProductListCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantRelationshipProductListsRestApi\MerchantRelationshipProductListsRestApiBusinessTester
     */
    protected MerchantRelationshipProductListsRestApiBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldExpandCustomerIdentifierWithProductListCollection(): void
    {
        // Arrange
        $customerIdentifierTransfer = (new CustomerIdentifierBuilder())->build();
        $customerTransfer = (new CustomerBuilder())->withCustomerProductListCollection()->build();

        // Act
        $customerIdentifierTransfer = $this->tester->getFacade()
            ->expandCustomerIdentifierWithCustomerProductListCollection($customerIdentifierTransfer, $customerTransfer);

        // Assert
        $this->assertSame(
            $customerTransfer->getCustomerProductListCollection(),
            $customerIdentifierTransfer->getCustomerProductListCollection(),
        );
    }

    /**
     * @return void
     */
    public function testShouldNotExpandCustomerIdentifierWithProductListCollection(): void
    {
        // Arrange
        $customerIdentifierTransfer = (new CustomerIdentifierBuilder())->build();
        $customerTransfer = (new CustomerBuilder())->build();

        // Act
        $customerIdentifierTransfer = $this->tester->getFacade()
            ->expandCustomerIdentifierWithCustomerProductListCollection($customerIdentifierTransfer, $customerTransfer);

        // Assert
        $this->assertEmpty($customerIdentifierTransfer->getCustomerProductListCollection());
    }
}
