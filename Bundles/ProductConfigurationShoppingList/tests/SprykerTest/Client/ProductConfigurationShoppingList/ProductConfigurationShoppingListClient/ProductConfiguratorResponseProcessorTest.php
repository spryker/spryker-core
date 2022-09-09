<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationShoppingList\ProductConfigurationShoppingListClient;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToCustomerClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToShoppingListClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Processor\ProductConfiguratorResponseProcessor;
use Spryker\Client\ProductConfigurationShoppingList\Updater\ShoppingListItemProductConfigurationUpdater;
use Spryker\Client\ProductConfigurationShoppingList\Validator\ProductConfiguratorResponseValidator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationShoppingList
 * @group ProductConfigurationShoppingListClient
 * @group ProductConfiguratorResponseProcessorTest
 * Add your own group annotations below this line
 */
class ProductConfiguratorResponseProcessorTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SHOPPING_LIST_ITEM_UUID = 'FAKE_SHOPPING_LIST_ITEM_UUID';

    /**
     * @var int
     */
    protected const FAKE_ID_SHOPPING_LIST = 12345;

    /**
     * @var string
     */
    protected const SHOPPING_LIST_ITEM_UUID = 'SHOPPING_LIST_ITEM_UUID';

    /**
     * @see \Spryker\Client\ProductConfigurationShoppingList\Validator\ProductConfiguratorResponseValidator::GLOSSARY_KEY_RESPONSE_VALIDATION_ERROR
     *
     * @var string
     */
    protected const GLOSSARY_KEY_RESPONSE_VALIDATION_ERROR = 'product_configuration.response.validation.error';

    /**
     * @see \Spryker\Client\ProductConfigurationShoppingList\Processor\ProductConfiguratorResponseProcessor::GLOSSARY_KEY_SHOPPING_LIST_ITEM_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_SHOPPING_LIST_ITEM_NOT_FOUND = 'product_configuration_shopping_list.error.item_not_found';

    /**
     * @return void
     */
    public function testProcessProductConfiguratorCheckSumResponseWithInvalidProductConfiguratorCheckSum(): void
    {
        // Arrange

        // Act
        $productConfiguratorResponseProcessorResponseTransfer = $this
            ->createProductConfiguratorResponseProcessorMock()
            ->processProductConfiguratorCheckSumResponse(
                new ProductConfiguratorResponseTransfer(),
                [],
            );

        // Assert
        $this->assertFalse($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
        $this->assertEmpty($productConfiguratorResponseProcessorResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testProcessProductConfiguratorCheckSumResponseWithoutMandatoryShoppingListItemUuid(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setProductConfigurationInstance(new ProductConfigurationInstanceTransfer());

        // Act
        $productConfiguratorResponseProcessorResponseTransfer = $this
            ->createProductConfiguratorResponseProcessorMock(true)
            ->processProductConfiguratorCheckSumResponse(
                $productConfiguratorResponseTransfer,
                [],
            );

        // Assert
        $this->assertFalse($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_RESPONSE_VALIDATION_ERROR,
            $productConfiguratorResponseProcessorResponseTransfer->getMessages()->offsetGet(0)->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testProcessProductConfiguratorCheckSumResponseWithFakeShoppingListItemUuid(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setProductConfigurationInstance(new ProductConfigurationInstanceTransfer());

        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setUuid(static::FAKE_SHOPPING_LIST_ITEM_UUID)
            ->setFkShoppingList(static::FAKE_ID_SHOPPING_LIST)
            ->setQuantity(2);

        // Act
        $productConfiguratorResponseProcessorResponseTransfer = $this
            ->createProductConfiguratorResponseProcessorMock(true, $shoppingListItemTransfer)
            ->processProductConfiguratorCheckSumResponse(
                $productConfiguratorResponseTransfer,
                [
                    ProductConfiguratorResponseTransfer::SHOPPING_LIST_ITEM_UUID => static::FAKE_SHOPPING_LIST_ITEM_UUID,
                ],
            );

        // Assert
        $this->assertFalse($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
        $this->assertEmpty($productConfiguratorResponseProcessorResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testProcessProductConfiguratorCheckSumResponseWithFakeShoppingListItemUuidWihtoutPersistedItem(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setProductConfigurationInstance(new ProductConfigurationInstanceTransfer());

        // Act
        $productConfiguratorResponseProcessorResponseTransfer = $this
            ->createProductConfiguratorResponseProcessorMock(true)
            ->processProductConfiguratorCheckSumResponse(
                $productConfiguratorResponseTransfer,
                [
                    ProductConfiguratorResponseTransfer::SHOPPING_LIST_ITEM_UUID => static::FAKE_SHOPPING_LIST_ITEM_UUID,
                ],
            );

        // Assert
        $this->assertFalse($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_SHOPPING_LIST_ITEM_NOT_FOUND,
            $productConfiguratorResponseProcessorResponseTransfer->getMessages()->offsetGet(0)->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testProcessProductConfiguratorCheckSumResponseWithShoppingListItemUuid(): void
    {
        // Arrange
        $productConfiguratorResponseTransfer = (new ProductConfiguratorResponseTransfer())
            ->setProductConfigurationInstance(new ProductConfigurationInstanceTransfer());

        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setUuid(static::SHOPPING_LIST_ITEM_UUID)
            ->setFkShoppingList(static::FAKE_ID_SHOPPING_LIST)
            ->setQuantity(2);

        // Act
        $productConfiguratorResponseProcessorResponseTransfer = $this
            ->createProductConfiguratorResponseProcessorMock(true, $shoppingListItemTransfer)
            ->processProductConfiguratorCheckSumResponse(
                $productConfiguratorResponseTransfer,
                [
                    ProductConfiguratorResponseTransfer::SHOPPING_LIST_ITEM_UUID => static::SHOPPING_LIST_ITEM_UUID,
                ],
            );

        // Assert
        $this->assertTrue($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
    }

    /**
     * @param bool|null $isIsSuccessful
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer|null $shoppingListItemTransfer
     *
     * @return \Spryker\Client\ProductConfigurationShoppingList\Processor\ProductConfiguratorResponseProcessor|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfiguratorResponseProcessorMock(
        ?bool $isIsSuccessful = false,
        ?ShoppingListItemTransfer $shoppingListItemTransfer = null
    ): ProductConfiguratorResponseProcessor {
        return $this->getMockBuilder(ProductConfiguratorResponseProcessor::class)
            ->setConstructorArgs([
                $this->createProductConfigurationShoppingListToProductConfigurationClientInterfaceMock(),
                $this->createProductConfiguratorResponseValidatorMock($isIsSuccessful),
                $this->createShoppingListItemProductConfigurationUpdaterMock($shoppingListItemTransfer),
            ])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer|null $shoppingListItemTransfer
     *
     * @return \Spryker\Client\ProductConfigurationShoppingList\Validator\ProductConfiguratorResponseValidator|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createShoppingListItemProductConfigurationUpdaterMock(
        ?ShoppingListItemTransfer $shoppingListItemTransfer = null
    ): ShoppingListItemProductConfigurationUpdater {
        return $this->getMockBuilder(ShoppingListItemProductConfigurationUpdater::class)
            ->setConstructorArgs([
                $this->createProductConfigurationShoppingListToShoppingListClientInterfaceMock($shoppingListItemTransfer),
                $this->createProductConfigurationShoppingListToCustomerClientInterfaceMock(),
            ])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @param bool|null $isIsSuccessful
     *
     * @return \Spryker\Client\ProductConfigurationShoppingList\Validator\ProductConfiguratorResponseValidator|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfiguratorResponseValidatorMock(
        ?bool $isIsSuccessful = false
    ): ProductConfiguratorResponseValidator {
        return $this->getMockBuilder(ProductConfiguratorResponseValidator::class)
            ->setConstructorArgs([
                $this->createProductConfigurationShoppingListToProductConfigurationClientInterfaceMock($isIsSuccessful),
            ])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer|null $shoppingListItemTransfer
     *
     * @return \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToShoppingListClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfigurationShoppingListToShoppingListClientInterfaceMock(
        ?ShoppingListItemTransfer $shoppingListItemTransfer = null
    ): ProductConfigurationShoppingListToShoppingListClientInterface {
        $productConfigurationWishlistToShoppingListClientInterfaceMock = $this
            ->getMockBuilder(ProductConfigurationShoppingListToShoppingListClientInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getShoppingListItemCollectionByUuid', 'updateShoppingListItemByUuid'])
            ->getMock();

        $productConfigurationWishlistToShoppingListClientInterfaceMock
            ->expects($this->any())
            ->method('updateShoppingListItemByUuid')
            ->willReturnCallback(function (ShoppingListItemTransfer $shoppingListItemTransfer) {
                return (new ShoppingListItemResponseTransfer())
                    ->setIsSuccess($shoppingListItemTransfer->getUuidOrFail() === static::SHOPPING_LIST_ITEM_UUID)
                    ->setShoppingListItem($shoppingListItemTransfer);
            });

        $shoppingListItemCollectionTransfer = new ShoppingListItemCollectionTransfer();

        if ($shoppingListItemTransfer) {
            $shoppingListItemCollectionTransfer->addItem($shoppingListItemTransfer);
        }

        $productConfigurationWishlistToShoppingListClientInterfaceMock->expects($this->any())
            ->method('getShoppingListItemCollectionByUuid')
            ->willReturn($shoppingListItemCollectionTransfer);

        return $productConfigurationWishlistToShoppingListClientInterfaceMock;
    }

    /**
     * @param bool|null $isIsSuccessful
     *
     * @return \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfigurationShoppingListToProductConfigurationClientInterfaceMock(
        ?bool $isIsSuccessful = false
    ): ProductConfigurationShoppingListToProductConfigurationClientInterface {
        $productConfigurationWishlistToProductConfigurationClientInterfaceMock = $this
            ->getMockBuilder(ProductConfigurationShoppingListToProductConfigurationClientInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'validateProductConfiguratorCheckSumResponse',
                'mapProductConfiguratorCheckSumResponse',
                'sendProductConfiguratorAccessTokenRequest',
            ])
            ->getMock();

        $productConfigurationWishlistToProductConfigurationClientInterfaceMock->expects($this->any())
            ->method('validateProductConfiguratorCheckSumResponse')
            ->willReturnCallback(function (ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer, array $configuratorResponseData) use ($isIsSuccessful) {
                return $productConfiguratorResponseProcessorResponseTransfer->setIsSuccessful($isIsSuccessful);
            });

        $productConfigurationWishlistToProductConfigurationClientInterfaceMock->expects($this->any())
            ->method('mapProductConfiguratorCheckSumResponse')
            ->willReturnCallback(function (array $configuratorResponseData, ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer) {
                return $productConfiguratorResponseTransfer->fromArray($configuratorResponseData);
            });

        return $productConfigurationWishlistToProductConfigurationClientInterfaceMock;
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToCustomerClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfigurationShoppingListToCustomerClientInterfaceMock(): ProductConfigurationShoppingListToCustomerClientInterface
    {
        $productConfigurationShoppingListToCustomerClientInterfaceMock = $this
            ->getMockBuilder(ProductConfigurationShoppingListToCustomerClientInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods([
                'getCustomer',
            ])
            ->getMock();

        $productConfigurationShoppingListToCustomerClientInterfaceMock->expects($this->any())
            ->method('getCustomer')
            ->willReturn((new CustomerTransfer())->setCompanyUserTransfer((new CompanyUserTransfer())));

        return $productConfigurationShoppingListToCustomerClientInterfaceMock;
    }
}
