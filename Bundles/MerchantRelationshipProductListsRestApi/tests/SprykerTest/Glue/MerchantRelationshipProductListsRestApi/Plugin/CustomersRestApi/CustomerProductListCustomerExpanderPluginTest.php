<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\MerchantRelationshipProductListsRestApi\Plugin\CustomersRestApi;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\CustomerProductListCollectionBuilder;
use Generated\Shared\DataBuilder\RestUserBuilder;
use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantRelationshipProductListsRestApi\Plugin\CustomersRestApi\CustomerProductListCustomerExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group MerchantRelationshipProductListsRestApi
 * @group Plugin
 * @group CustomersRestApi
 * @group CustomerProductListCustomerExpanderPluginTest
 * Add your own group annotations below this line
 */
class CustomerProductListCustomerExpanderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testExpandShouldReturnCustomerWithProductListCollection(): void
    {
        // Arrange
        $restUserTransfer = $this->createRestUser();
        $restRequest = $this->createRestRequestMock();
        $customerTransfer = (new CustomerBuilder())->build();

        $restRequest->expects($this->once())->method('getRestUser')->willReturn($restUserTransfer);

        // Act
        $customerTransfer = (new CustomerProductListCustomerExpanderPlugin())->expand($customerTransfer, $restRequest);

        // Assert
        $this->assertSame($restUserTransfer->getCustomerProductListCollection(), $customerTransfer->getCustomerProductListCollection());
    }

    /**
     * @return void
     */
    public function testExpandShouldReturnCustomerWithoutProductListCollectionWhileRestUserUndefined(): void
    {
        // Arrange
        $restRequest = $this->createRestRequestMock();
        $customerTransfer = (new CustomerBuilder())->build();

        // Act
        $customerTransfer = (new CustomerProductListCustomerExpanderPlugin())->expand($customerTransfer, $restRequest);

        // Assert
        $this->assertEmpty($customerTransfer->getCustomerProductListCollection());
    }

    /**
     * @return \Generated\Shared\Transfer\RestUserTransfer
     */
    protected function createRestUser(): RestUserTransfer
    {
        $restUserTransfer = (new RestUserBuilder())->build();
        $customerProductListCollectionTransfer = (new CustomerProductListCollectionBuilder())->build();

        return $restUserTransfer->setCustomerProductListCollection($customerProductListCollectionTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createRestRequestMock(): RestRequestInterface
    {
        return $this->getMockBuilder(RestRequestInterface::class)->getMock();
    }
}
