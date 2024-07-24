<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ServicePointsRestApi\Plugin\CheckoutRestApi;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestServicePointTransfer;
use Spryker\Glue\ServicePointsRestApi\Plugin\CheckoutRestApi\ServicePointCheckoutRequestAttributesValidatorPlugin;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;
use SprykerTest\Glue\ServicePointsRestApi\ServicePointsRestApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ServicePointsRestApi
 * @group Plugin
 * @group CheckoutRestApi
 * @group ServicePointCheckoutRequestAttributesValidatorPluginTest
 * Add your own group annotations below this line
 */
class ServicePointCheckoutRequestAttributesValidatorPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SERVICE_POINT_ID_1 = 'TEST_SERVICE_POINT_ID_1';

    /**
     * @var string
     */
    protected const TEST_SERVICE_POINT_ID_2 = 'TEST_SERVICE_POINT_ID_2';

    /**
     * @var string
     */
    protected const TEST_QUOTE_ITEM_GROUP_KEY = 'TEST_QUOTE_ITEM_GROUP_KEY';

    /**
     * @var \SprykerTest\Glue\ServicePointsRestApi\ServicePointsRestApiTester
     */
    protected ServicePointsRestApiTester $tester;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->tester->mockStoreClient();
    }

    /**
     * @return void
     */
    public function testValidateAttributesReturnsNoErrorsWhenNoServicePointsAreProvided(): void
    {
        // Arrange
        $restCheckoutRequestAttributesTransfer = new RestCheckoutRequestAttributesTransfer();

        // Act
        $restErrorCollectionTransfer = (new ServicePointCheckoutRequestAttributesValidatorPlugin())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertCount(0, $restErrorCollectionTransfer->getRestErrors());
    }

    /**
     * @return void
     */
    public function testValidateAttributesReturnsNoErrorsWhenProvidedServicePointsAreAvailableForTheCurrentStore(): void
    {
        // Arrange
        $restServicePointTransfer = (new RestServicePointTransfer())
            ->setIdServicePoint(static::TEST_SERVICE_POINT_ID_1)
            ->setItems([static::TEST_QUOTE_ITEM_GROUP_KEY]);
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->addServicePoint($restServicePointTransfer);

        $this->tester->mockGetServicePointStorageCollection($restCheckoutRequestAttributesTransfer->getServicePoints());

        // Act
        $restErrorCollectionTransfer = (new ServicePointCheckoutRequestAttributesValidatorPlugin())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertCount(0, $restErrorCollectionTransfer->getRestErrors());
    }

    /**
     * @return void
     */
    public function testValidateAttributesReturnsBadRequestWhenProvidedServicePointsAreNotAvailableForTheCurrentStore(): void
    {
        // Arrange
        $restServicePointTransfer = (new RestServicePointTransfer())
            ->setIdServicePoint(static::TEST_SERVICE_POINT_ID_1)
            ->setItems([static::TEST_QUOTE_ITEM_GROUP_KEY]);
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->addServicePoint($restServicePointTransfer);

        $this->tester->mockGetServicePointStorageCollection(new ArrayObject());

        // Act
        $restErrorCollectionTransfer = (new ServicePointCheckoutRequestAttributesValidatorPlugin())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertCount(1, $restErrorCollectionTransfer->getRestErrors());
        $this->tester->assertRestErrorMessageTransfer(
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current(),
            ServicePointsRestApiConfig::RESPONSE_CODE_SERVICE_POINT_IS_UNAVAILABLE,
            ServicePointsRestApiConfig::RESPONSE_DETAILS_SERVICE_POINT_IS_UNAVAILABLE,
        );
    }

    /**
     * @return void
     */
    public function testValidateAttributesReturnsBadRequestWhenItemsProvidedForTwoDifferentServicePointsAreDuplicated(): void
    {
        // Arrange
        $restServicePointTransfer1 = (new RestServicePointTransfer())
            ->setIdServicePoint(static::TEST_SERVICE_POINT_ID_1)
            ->setItems([static::TEST_QUOTE_ITEM_GROUP_KEY]);
        $restServicePointTransfer2 = (new RestServicePointTransfer())
            ->setIdServicePoint(static::TEST_SERVICE_POINT_ID_2)
            ->setItems([static::TEST_QUOTE_ITEM_GROUP_KEY]);
        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->addServicePoint($restServicePointTransfer1)
            ->addServicePoint($restServicePointTransfer2);

        $this->tester->mockGetServicePointStorageCollection($restCheckoutRequestAttributesTransfer->getServicePoints());

        // Act
        $restErrorCollectionTransfer = (new ServicePointCheckoutRequestAttributesValidatorPlugin())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertCount(1, $restErrorCollectionTransfer->getRestErrors());
        $this->tester->assertRestErrorMessageTransfer(
            $restErrorCollectionTransfer->getRestErrors()->getIterator()->current(),
            ServicePointsRestApiConfig::RESPONSE_CODE_SERVICE_POINT_ITEM_IS_DUPLICATED,
            ServicePointsRestApiConfig::RESPONSE_DETAILS_SERVICE_POINT_ITEM_IS_DUPLICATED,
        );
    }
}
