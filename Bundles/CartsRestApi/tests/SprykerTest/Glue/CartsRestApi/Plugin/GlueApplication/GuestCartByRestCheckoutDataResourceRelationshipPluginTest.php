<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CartsRestApi\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Spryker\Glue\CartsRestApi\Plugin\GlueApplication\GuestCartByRestCheckoutDataResourceRelationshipPlugin;
use SprykerTest\Glue\CartsRestApi\CartRestApiGlueTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CartsRestApi
 * @group Plugin
 * @group GlueApplication
 * @group GuestCartByRestCheckoutDataResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class GuestCartByRestCheckoutDataResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\CartsRestApi\CartsRestApiConfig::RESOURCE_GUEST_CARTS
     *
     * @var string
     */
    protected const RESOURCE_GUEST_CARTS = 'guest-carts';

    /**
     * @var string
     */
    protected const CART_UUID = 'cart_uuid';

    /**
     * @var \SprykerTest\Glue\CartsRestApi\CartRestApiGlueTester
     */
    protected CartRestApiGlueTester $tester;

    /**
     * @return void
     */
    public function testAddResourceRelationshipsWillAddGuestCartsResourceIfQuoteIsProvided(): void
    {
        // Arrange
        $restCheckoutDataTransfer = $this->tester->getRestCheckoutDataTransfer([
            RestCheckoutDataTransfer::QUOTE => $this->tester->createQuoteTransfer(static::CART_UUID),
        ]);
        $restCheckoutDataResource = $this->tester->createCheckoutDataRestResource()
            ->setPayload($restCheckoutDataTransfer);

        // Act
        (new GuestCartByRestCheckoutDataResourceRelationshipPlugin())->addResourceRelationships(
            [$restCheckoutDataResource],
            $this->tester->getRestRequestMock(),
        );

        // Assert
        $relationship = $restCheckoutDataResource->getRelationshipByType(static::RESOURCE_GUEST_CARTS);
        $this->assertCount(1, $relationship);
        $this->assertEquals(static::CART_UUID, current($relationship)->getId());
        $this->assertEquals($restCheckoutDataTransfer->getQuote()->getUuid(), current($relationship)->getPayload()->getUuid());
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsWillNotAddGuestCartsResourcesIfQuoteIsNotProvided(): void
    {
        // Arrange
        $restCheckoutDataTransfer = $this->tester->getRestCheckoutDataTransfer([
            RestCheckoutDataTransfer::QUOTE => null,
        ]);
        $restCheckoutDataResource = $this->tester->createCheckoutDataRestResource()
            ->setPayload($restCheckoutDataTransfer);

        // Act
        (new GuestCartByRestCheckoutDataResourceRelationshipPlugin())->addResourceRelationships(
            [$restCheckoutDataResource],
            $this->tester->getRestRequestMock(),
        );

        // Assert
        $this->assertEmpty($restCheckoutDataResource->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsWillNotAddGuestCartsResourcesIfRestCheckoutDataTransferIsNotProvided(): void
    {
        // Arrange
        $restCheckoutDataResource = $this->tester->createCheckoutDataRestResource();

        // Act
        (new GuestCartByRestCheckoutDataResourceRelationshipPlugin())->addResourceRelationships(
            [$restCheckoutDataResource],
            $this->tester->getRestRequestMock(),
        );

        // Assert
        $this->assertEmpty($restCheckoutDataResource->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddResourceRelationshipsWillNotAddGuestCartsResourcesForAuthorisedUser(): void
    {
        // Arrange
        $restCheckoutDataTransfer = $this->tester->getRestCheckoutDataTransfer([
            RestCheckoutDataTransfer::QUOTE => $this->tester->createQuoteTransfer(static::CART_UUID),
        ]);
        $restCheckoutDataResource = $this->tester->createCheckoutDataRestResource()
            ->setPayload($restCheckoutDataTransfer);

        // Act
        (new GuestCartByRestCheckoutDataResourceRelationshipPlugin())->addResourceRelationships(
            [$restCheckoutDataResource],
            $this->tester->getRestRequestMock(true),
        );

        // Assert
        $this->assertEmpty($restCheckoutDataResource->getRelationships());
    }
}
