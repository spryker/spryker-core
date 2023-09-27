<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsRestApi\Plugin\GlueApplication;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\ServicePointsRestApi\Plugin\CheckoutRestApi\ServicePointCheckoutDataResponseMapperPlugin;
use SprykerTest\Glue\ServicePointsRestApi\ServicePointsRestApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ServicePointsRestApi
 * @group Plugin
 * @group GlueApplication
 * @group ServicePointCheckoutDataResponseMapperPluginTest
 * Add your own group annotations below this line
 */
class ServicePointCheckoutDataResponseMapperPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SERVICE_POINT_UUID_1 = 'TEST_SERVICE_POINT_UUID_1';

    /**
     * @var string
     */
    protected const TEST_SERVICE_POINT_UUID_2 = 'TEST_SERVICE_POINT_UUID_2';

    /**
     * @var \SprykerTest\Glue\ServicePointsRestApi\ServicePointsRestApiTester
     */
    protected ServicePointsRestApiTester $tester;

    /**
     * @return void
     */
    public function testReturnsRestCheckoutDataResponseAttributesTransferWithAllSelectedServicePoints(): void
    {
        // Arrange
        $restCheckoutDataTransfer = (new RestCheckoutDataTransfer())->setQuote(
            (new QuoteBuilder())
                ->withItem([
                    ItemTransfer::SERVICE_POINT => (new ServicePointBuilder(
                        [ServicePointTransfer::UUID => static::TEST_SERVICE_POINT_UUID_1],
                    ))->build()])
                ->withAnotherItem([
                    ItemTransfer::SERVICE_POINT => (new ServicePointBuilder(
                        [ServicePointTransfer::UUID => static::TEST_SERVICE_POINT_UUID_2],
                    ))->build()])
                ->build(),
        );

        // Act
        $restCheckoutDataResponseAttributesTransfer = (new ServicePointCheckoutDataResponseMapperPlugin())
            ->mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
                $restCheckoutDataTransfer,
                new RestCheckoutRequestAttributesTransfer(),
                new RestCheckoutDataResponseAttributesTransfer(),
            );

        // Assert
        $this->assertEquals(2, $restCheckoutDataResponseAttributesTransfer->getSelectedServicePoints()->count());
        $this->assertEmpty(array_diff(
            [static::TEST_SERVICE_POINT_UUID_1, static::TEST_SERVICE_POINT_UUID_2],
            $this->getSelectedServicePointsUuids($restCheckoutDataResponseAttributesTransfer),
        ));
    }

    /**
     * @return void
     */
    public function testReturnsRestCheckoutDataResponseAttributesTransferWithSelectedServicePointsOnly(): void
    {
        // Arrange
        $restCheckoutDataTransfer = (new RestCheckoutDataTransfer())->setQuote(
            (new QuoteBuilder())
                ->withItem([
                    ItemTransfer::SERVICE_POINT => (new ServicePointBuilder(
                        [ServicePointTransfer::UUID => static::TEST_SERVICE_POINT_UUID_1],
                    ))->build()])
                ->withAnotherItem()
                ->build(),
        );

        // Act
        $restCheckoutDataResponseAttributesTransfer = (new ServicePointCheckoutDataResponseMapperPlugin())
            ->mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
                $restCheckoutDataTransfer,
                new RestCheckoutRequestAttributesTransfer(),
                new RestCheckoutDataResponseAttributesTransfer(),
            );

        // Assert
        $selectedServicePoints = $restCheckoutDataResponseAttributesTransfer->getSelectedServicePoints();
        $this->assertEquals(1, $selectedServicePoints->count());
        $this->assertEquals(
            static::TEST_SERVICE_POINT_UUID_1,
            $selectedServicePoints->getIterator()->current()->getIdServicePoint(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsRestCheckoutDataResponseAttributesTransferWithoutSelectedServicePointsIfQuoteItemsAreNotProvided(): void
    {
        // Act
        $restCheckoutDataResponseAttributesTransfer = (new ServicePointCheckoutDataResponseMapperPlugin())
            ->mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
                (new RestCheckoutDataTransfer())->setQuote(new QuoteTransfer()),
                new RestCheckoutRequestAttributesTransfer(),
                new RestCheckoutDataResponseAttributesTransfer(),
            );

        // Assert
        $this->assertEmpty($restCheckoutDataResponseAttributesTransfer->getSelectedServicePoints()->count());
    }

    /**
     * @return void
     */
    public function testReturnsRestCheckoutDataResponseAttributesTransferWithoutSelectedServicePointsIfNoOneOfQuoteItemsHaveServicePoints(): void
    {
        // Arrange
        $restCheckoutDataTransfer = (new RestCheckoutDataTransfer())->setQuote(
            (new QuoteBuilder())->withItem()->build(),
        );

        // Act
        $restCheckoutDataResponseAttributesTransfer = (new ServicePointCheckoutDataResponseMapperPlugin())
            ->mapRestCheckoutDataResponseTransferToRestCheckoutDataResponseAttributesTransfer(
                $restCheckoutDataTransfer,
                new RestCheckoutRequestAttributesTransfer(),
                new RestCheckoutDataResponseAttributesTransfer(),
            );

        // Assert
        $this->assertEmpty($restCheckoutDataResponseAttributesTransfer->getSelectedServicePoints()->count());
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
     *
     * @return list<string>
     */
    protected function getSelectedServicePointsUuids(
        RestCheckoutDataResponseAttributesTransfer $restCheckoutDataResponseAttributesTransfer
    ): array {
        $servicePointUuids = [];
        foreach ($restCheckoutDataResponseAttributesTransfer->getSelectedServicePoints() as $restServicePointTransfer) {
            $servicePointUuids[] = $restServicePointTransfer->getIdServicePoint();
        }

        return $servicePointUuids;
    }
}
