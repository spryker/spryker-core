<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CartsRestApi\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Spryker\Glue\CartsRestApi\Plugin\GlueApplication\CartByRestCheckoutDataResourceRelationshipPlugin;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use SprykerTest\Glue\CartsRestApi\CartRestApiGlueTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CartsRestApi
 * @group Plugin
 * @group GlueApplication
 * @group CartByRestCheckoutDataResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class CartByRestCheckoutDataResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESOURCE_CARTS
     *
     * @var string
     */
    protected const RESOURCE_CARTS = 'carts';

    /**
     * @uses \Spryker\Glue\GlueApplication\Plugin\Application\GlueApplicationApplicationPlugin::SERVICE_RESOURCE_BUILDER
     *
     * @var string
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @var string
     */
    protected const QUOTE_UUID = 'test_cart_uuid';

    /**
     * @var \SprykerTest\Glue\CartsRestApi\CartRestApiGlueTester
     */
    protected CartRestApiGlueTester $tester;

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
    public function testAddResourceRelationshipsWillAddCartsResource(): void
    {
        // Arrange
        $restCheckoutDataTransfer = $this->tester->getRestCheckoutDataTransfer([
            RestCheckoutDataTransfer::QUOTE => $this->tester->createQuoteTransfer(static::QUOTE_UUID),
        ]);
        $restCheckoutDataResource = $this->tester->createCheckoutDataRestResource()
            ->setPayload($restCheckoutDataTransfer);

        // Act
        (new CartByRestCheckoutDataResourceRelationshipPlugin())->addResourceRelationships(
            [$restCheckoutDataResource],
            $this->tester->getRestRequestMock(true),
        );

        // Assert
        $this->assertCount(1, $restCheckoutDataResource->getRelationshipByType(static::RESOURCE_CARTS));
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsWillNotAddCartResourcesWithoutQuoteInRestCheckoutDataPayload(): void
    {
        // Arrange
        $restCheckoutDataTransfer = $this->tester->getRestCheckoutDataTransfer();
        $restCheckoutDataResource = $this->tester->createCheckoutDataRestResource()
            ->setPayload($restCheckoutDataTransfer);

        // Act
        (new CartByRestCheckoutDataResourceRelationshipPlugin())->addResourceRelationships(
            [$restCheckoutDataResource],
            $this->tester->getRestRequestMock(true),
        );

        // Assert
        $this->assertEmpty($restCheckoutDataResource->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsWillNotAddCartsResourcesIfRestCheckoutDataTransferIsNotProvided(): void
    {
        // Arrange
        $restCheckoutDataResource = $this->tester->createCheckoutDataRestResource();

        // Act
        (new CartByRestCheckoutDataResourceRelationshipPlugin())->addResourceRelationships(
            [$restCheckoutDataResource],
            $this->tester->getRestRequestMock(true),
        );

        // Assert
        $this->assertEmpty($restCheckoutDataResource->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsWillNotAddCartsResourcesForGuestUser(): void
    {
        // Arrange
        $restCheckoutDataTransfer = $this->tester->getRestCheckoutDataTransfer([
            RestCheckoutDataTransfer::QUOTE => $this->tester->createQuoteTransfer(static::QUOTE_UUID),
        ]);
        $restCheckoutDataResource = $this->tester->createCheckoutDataRestResource()
            ->setPayload($restCheckoutDataTransfer);

        // Act
        (new CartByRestCheckoutDataResourceRelationshipPlugin())->addResourceRelationships(
            [$restCheckoutDataResource],
            $this->tester->getRestRequestMock(),
        );

        // Assert
        $this->assertEmpty($restCheckoutDataResource->getRelationships());
    }
}
