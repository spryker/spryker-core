<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\OrderAmendmentsRestApi\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\OrderAmendmentsRestApi\Plugin\GlueApplication\OrderAmendmentsByOrderResourceRelationshipPlugin;
use SprykerTest\Glue\OrderAmendmentsRestApi\OrderAmendmentsRestApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group OrderAmendmentsRestApi
 * @group Plugin
 * @group GlueApplication
 * @group OrderAmendmentsByOrderResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentsByOrderResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\GlueApplication\Plugin\Application\GlueApplicationApplicationPlugin::SERVICE_RESOURCE_BUILDER
     *
     * @var string
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @var string
     */
    protected const TEST_CREATED_AT = '2024-01-01T00:00:00+00:00';

    /**
     * @var string
     */
    protected const TEST_UPDATED_AT = '2024-01-02T00:00:00+00:00';

    /**
     * @uses \Spryker\Glue\OrderAmendmentsRestApi\OrderAmendmentsRestApiConfig::RESOURCE_ORDER_AMENDMENTS
     *
     * @var string
     */
    protected const RESOURCE_ORDER_AMENDMENTS = 'order-amendments';

    /**
     * @var \SprykerTest\Glue\OrderAmendmentsRestApi\OrderAmendmentsRestApiTester
     */
    protected OrderAmendmentsRestApiTester $tester;

    /**
     * @return void
     */
    public function _before(): void
    {
        parent::_before();

        $this->tester->getContainer()->set(
            static::SERVICE_RESOURCE_BUILDER,
            new RestResourceBuilder(),
        );
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsAddsOrderAmendmentsRelationshipWhenOrderAmendmentIsProvidedInPayload(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())->setSalesOrderAmendment(
            (new SalesOrderAmendmentTransfer())
                ->setCreatedAt(static::TEST_CREATED_AT)
                ->setUpdatedAt(static::TEST_UPDATED_AT),
        );
        $restResource = $this->tester->createRestResource()->setPayload($orderTransfer);

        // Act
        (new OrderAmendmentsByOrderResourceRelationshipPlugin())->addResourceRelationships(
            [$restResource],
            $this->tester->createRestRequest(),
        );

        // Assert
        $this->assertCount(1, $restResource->getRelationships());

        $restResources = $restResource->getRelationshipByType(static::RESOURCE_ORDER_AMENDMENTS);
        $this->assertCount(1, $restResources);

        /** @var \Generated\Shared\Transfer\RestOrderAmendmentsAttributesTransfer $restOrderAmendmentsAttributesTransfer */
        $restOrderAmendmentsAttributesTransfer = $restResources[0]->getAttributes();
        $this->assertSame(static::TEST_CREATED_AT, $restOrderAmendmentsAttributesTransfer->getCreatedAt());
        $this->assertSame(static::TEST_UPDATED_AT, $restOrderAmendmentsAttributesTransfer->getUpdatedAt());
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsDoesNotAddAnyRelationshipsWhenOrderTransferIsNotProvidedAsPayload(): void
    {
        // Arrange
        $restResource = $this->tester->createRestResource();

        // Act
        (new OrderAmendmentsByOrderResourceRelationshipPlugin())->addResourceRelationships(
            [$restResource],
            $this->tester->createRestRequest(),
        );

        // Assert
        $this->assertCount(0, $restResource->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsDoesNotAddAnyRelationshipsWhenOrderAmendmentIsNotProvidedInOrderTransferPayload(): void
    {
        // Arrange
        $restResource = $this->tester->createRestResource()->setPayload(new OrderTransfer());

        // Act
        (new OrderAmendmentsByOrderResourceRelationshipPlugin())->addResourceRelationships(
            [$restResource],
            $this->tester->createRestRequest(),
        );

        // Assert
        $this->assertCount(0, $restResource->getRelationships());
    }
}
