<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationWishlist;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\WishlistItemResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientInterface;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface;
use Spryker\Client\ProductConfigurationWishlist\Processor\ProductConfiguratorResponseProcessor;
use Spryker\Client\ProductConfigurationWishlist\Validator\ProductConfiguratorResponseValidator;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationWishlist
 * @group ProductConfiguratorResponseProcessorTest
 * Add your own group annotations below this line
 */
class ProductConfiguratorResponseProcessorTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_SKU = 'SKU';

    /**
     * @var int
     */
    protected const FAKE_ID_WISHLIST_ITEM = 123456;

    /**
     * @var int
     */
    protected const ID_WISHLIST_ITEM = 333;

    /**
     * @see \Spryker\Client\ProductConfigurationWishlist\Validator\ProductConfiguratorResponseValidator::GLOSSARY_KEY_RESPONSE_VALIDATION_ERROR
     *
     * @var string
     */
    protected const GLOSSARY_KEY_RESPONSE_VALIDATION_ERROR = 'product_configuration.response.validation.error';

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
    public function testProcessProductConfiguratorCheckSumResponseWithoutMandatoryWishlistItemId(): void
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
                    ProductConfiguratorResponseTransfer::SKU => static::FAKE_SKU,
                ],
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
    public function testProcessProductConfiguratorCheckSumResponseWithoutMandatorySku(): void
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
                    ProductConfiguratorResponseTransfer::ID_WISHLIST_ITEM => static::ID_WISHLIST_ITEM,
                ],
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
    public function testProcessProductConfiguratorCheckSumResponseWithFakeWishlistItemId(): void
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
                    ProductConfiguratorResponseTransfer::ID_WISHLIST_ITEM => static::FAKE_ID_WISHLIST_ITEM,
                    ProductConfiguratorResponseTransfer::SKU => static::FAKE_SKU,
                ],
            );

        // Assert
        $this->assertFalse($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
        $this->assertEmpty($productConfiguratorResponseProcessorResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testProcessProductConfiguratorCheckSumResponseWithCorrectWishlistItemId(): void
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
                    ProductConfiguratorResponseTransfer::ID_WISHLIST_ITEM => static::ID_WISHLIST_ITEM,
                    ProductConfiguratorResponseTransfer::SKU => static::FAKE_SKU,
                ],
            );

        // Assert
        $this->assertTrue($productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful());
    }

    /**
     * @param bool|null $isIsSuccessful
     *
     * @return \Spryker\Client\ProductConfigurationWishlist\Processor\ProductConfiguratorResponseProcessor|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfiguratorResponseProcessorMock(
        ?bool $isIsSuccessful = false
    ): ProductConfiguratorResponseProcessor {
        return $this->getMockBuilder(ProductConfiguratorResponseProcessor::class)
            ->setConstructorArgs([
                $this->createProductConfigurationWishlistToProductConfigurationClientInterfaceMock(),
                $this->createProductConfiguratorResponseValidatorMock($isIsSuccessful),
                $this->createProductConfigurationWishlistToWishlistClientInterfaceMock(),
            ])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @param bool|null $isIsSuccessful
     *
     * @return \Spryker\Client\ProductConfigurationWishlist\Validator\ProductConfiguratorResponseValidator|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfiguratorResponseValidatorMock(
        ?bool $isIsSuccessful = false
    ): ProductConfiguratorResponseValidator {
        return $this->getMockBuilder(ProductConfiguratorResponseValidator::class)
            ->setConstructorArgs([
                $this->createProductConfigurationWishlistToProductConfigurationClientInterfaceMock($isIsSuccessful),
            ])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfigurationWishlistToWishlistClientInterfaceMock(): ProductConfigurationWishlistToWishlistClientInterface
    {
        $productConfigurationWishlistToWishlistClientInterfaceMock = $this
            ->getMockBuilder(ProductConfigurationWishlistToWishlistClientInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getWishlistItem', 'updateWishlistItem'])
            ->getMock();

        $productConfigurationWishlistToWishlistClientInterfaceMock->expects($this->any())
            ->method('updateWishlistItem')
            ->willReturnCallback(function (WishlistItemTransfer $wishlistItemTransfer) {
                return (new WishlistItemResponseTransfer())
                    ->setIsSuccess($wishlistItemTransfer->getIdWishlistItem() === static::ID_WISHLIST_ITEM)
                    ->setWishlistItem($wishlistItemTransfer);
            });

        return $productConfigurationWishlistToWishlistClientInterfaceMock;
    }

    /**
     * @param bool|null $isIsSuccessful
     *
     * @return \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductConfigurationWishlistToProductConfigurationClientInterfaceMock(
        ?bool $isIsSuccessful = false
    ): ProductConfigurationWishlistToProductConfigurationClientInterface {
        $productConfigurationWishlistToProductConfigurationClientInterfaceMock = $this
            ->getMockBuilder(ProductConfigurationWishlistToProductConfigurationClientInterface::class)
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
}
