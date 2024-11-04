<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Availability;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StorageAvailabilityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Availability\AvailabilityClient;
use Spryker\Client\Availability\AvailabilityClientInterface;
use Spryker\Client\Availability\AvailabilityDependencyProvider;
use Spryker\Client\Availability\AvailabilityFactory;
use Spryker\Client\Availability\Dependency\Client\AvailabilityToStorageInterface;
use Spryker\Client\Availability\Dependency\Client\AvailabilityToStoreClientInterface;
use Spryker\Client\Availability\Exception\ProductAvailabilityNotFoundException;
use Spryker\Client\Availability\KeyBuilder\AvailabilityResourceKeyBuilder;
use Spryker\Client\Availability\Storage\AvailabilityStorage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Availability
 * @group AvailabilityClientTest
 * Add your own group annotations below this line
 */
class AvailabilityClientTest extends Unit
{
    /**
     * @var int
     */
    public const ID_PRODUCT_ABSTRACT = 5;

    /**
     * @var \SprykerTest\Client\Availability\AvailabilityClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->addDependencies();
    }

    /**
     * @return void
     */
    public function testFindProductAvailabilityByIdProductAbstractReturnsProductAvailabilityTransferObject(): void
    {
        // Arrange
        $productAvailability = [];
        $this->setStorageReturn($productAvailability);

        // Act
        $actualProductAvailability = $this->createAvailabilityClient()->findProductAvailabilityByIdProductAbstract(static::ID_PRODUCT_ABSTRACT);

        // Assert
        $this->assertSame(StorageAvailabilityTransfer::class, get_class($actualProductAvailability));
    }

    /**
     * @return void
     */
    public function testFindProductAvailabilityByIdProductAbstractReturnsNullWhenProductAvailabilityWasNotFoundInStorage(): void
    {
        // Arrange
        $productAvailability = null;
        $this->setStorageReturn($productAvailability);

        // Act
        $actualResult = $this->createAvailabilityClient()->findProductAvailabilityByIdProductAbstract(static::ID_PRODUCT_ABSTRACT);

        // Assert
        $this->assertNull($actualResult);
    }

    /**
     * @return void
     */
    public function testGetProductAvailabilityByIdProductAbstractReturnsProductAvailabilityTransferObject(): void
    {
        // Arrange
        $productAvailability = [];
        $this->setStorageReturn($productAvailability);

        // Act
        $actualProductAvailability = $this->createAvailabilityClient()->getProductAvailabilityByIdProductAbstract(static::ID_PRODUCT_ABSTRACT);

        // Assert
        $this->assertSame(StorageAvailabilityTransfer::class, get_class($actualProductAvailability));
    }

    /**
     * @return void
     */
    public function testGetProductAvailabilityByIdProductAbstractThrowsExceptionWhenProductAvailabilityWasNotFoundInStorage(): void
    {
        // Arrange
        $productAvailability = null;
        $this->setStorageReturn($productAvailability);

        // Assert
        $this->expectException(ProductAvailabilityNotFoundException::class);

        // Act
        $this->createAvailabilityClient()->getProductAvailabilityByIdProductAbstract(static::ID_PRODUCT_ABSTRACT);
    }

    /**
     * @return void
     */
    public function testGetProductAvailabilityCallGenerateKeyContainsStoreName(): void
    {
        // Arrange
        $availabilityResourceKeyBuilderMock = $this->getAvailabilityResourceKeyBuilderMock();

        // Assert
        $availabilityResourceKeyBuilderMock->method('generateKey')
            ->with(
                $this->greaterThan(0),
                $this->stringContains($this->tester::DEFAULT_LOCALE_SHORT_NAME),
                $this->stringContains($this->tester::DEFAULT_STORE_NAME),
            )
            ->willReturn($this->tester::MOCK_RETURN_KEY);

        $availabilityToStoreClientMock = $this->getAvailabilityToStoreClientMock();

        $availabilityToStoreClientMock->method('getCurrentStore')
            ->willReturn((new StoreTransfer())->setName($this->tester::DEFAULT_STORE_NAME));

        $availabilityStorageMock = $this->createAvailabilityStorageMock(
            [],
            $availabilityResourceKeyBuilderMock,
            $this->tester::DEFAULT_LOCALE_NAME,
            $availabilityToStoreClientMock,
        );

        $factoryMock = $this->getFactoryMock($availabilityStorageMock);

        // Act
        $actualProductAvailability = $this->getAvailabilityClientWithMockFactory($factoryMock)->getProductAvailabilityByIdProductAbstract(static::ID_PRODUCT_ABSTRACT);

        // Assert
        $this->assertSame(StorageAvailabilityTransfer::class, get_class($actualProductAvailability));
    }

    /**
     * @return void
     */
    public function testGetProductAvailabilityCallGenerateKeyDoesNotContainStoreName(): void
    {
        // Arrange
        $availabilityResourceKeyBuilderMock = $this->getAvailabilityResourceKeyBuilderMock();

        // Assert
        $availabilityResourceKeyBuilderMock->method('generateKey')
            ->with(
                $this->greaterThan(0),
                $this->stringContains($this->tester::DEFAULT_LOCALE_SHORT_NAME),
            )
            ->willReturn($this->tester::MOCK_RETURN_KEY);

        $availabilityToStoreClientMock = $this->getAvailabilityToStoreClientMock();

        $availabilityToStoreClientMock->method('getCurrentStore')
            ->willReturn((new StoreTransfer())->setName($this->tester::DEFAULT_STORE_NAME));

        $availabilityStorageMock = $this->createAvailabilityStorageMock(
            [],
            $availabilityResourceKeyBuilderMock,
            $this->tester::DEFAULT_LOCALE_NAME,
            $availabilityToStoreClientMock,
        );

        $factoryMock = $this->getFactoryMock($availabilityStorageMock);
        // Act
        $actualProductAvailability = $this->getAvailabilityClientWithMockFactory($factoryMock)->getProductAvailabilityByIdProductAbstract(static::ID_PRODUCT_ABSTRACT);

        // Assert
        $this->assertSame(StorageAvailabilityTransfer::class, get_class($actualProductAvailability));
    }

    /**
     * @return void
     */
    public function testFindProductAvailabilityCallGenerateKeyContainsStoreName(): void
    {
        // Arrange
        $availabilityResourceKeyBuilderMock = $this->getAvailabilityResourceKeyBuilderMock();

        // Assert
        $availabilityResourceKeyBuilderMock->method('generateKey')
            ->with(
                $this->greaterThan(0),
                $this->stringContains($this->tester::DEFAULT_LOCALE_SHORT_NAME),
                $this->stringContains($this->tester::DEFAULT_STORE_NAME),
            )
            ->willReturn($this->tester::MOCK_RETURN_KEY);

        $availabilityToStoreClientMock = $this->getAvailabilityToStoreClientMock();

        $availabilityToStoreClientMock->method('getCurrentStore')
            ->willReturn((new StoreTransfer())->setName($this->tester::DEFAULT_STORE_NAME));

        $availabilityStorageMock = $this->createAvailabilityStorageMock(
            [],
            $availabilityResourceKeyBuilderMock,
            $this->tester::DEFAULT_LOCALE_NAME,
            $availabilityToStoreClientMock,
        );

        $factoryMock = $this->getFactoryMock($availabilityStorageMock);

        // Act
        $actualProductAvailability = $this->getAvailabilityClientWithMockFactory($factoryMock)->findProductAvailabilityByIdProductAbstract(static::ID_PRODUCT_ABSTRACT);

        // Assert
        $this->assertSame(StorageAvailabilityTransfer::class, get_class($actualProductAvailability));
    }

    /**
     * @return void
     */
    public function testFindProductAvailabilityCallGenerateKeyDoesNotContainStoreName(): void
    {
        // Arrange
        $availabilityResourceKeyBuilderMock = $this->getAvailabilityResourceKeyBuilderMock();

        // Assert
        $availabilityResourceKeyBuilderMock->method('generateKey')
            ->with(
                $this->greaterThan(0),
                $this->stringContains($this->tester::DEFAULT_LOCALE_SHORT_NAME),
            )
            ->willReturn($this->tester::MOCK_RETURN_KEY);

        $availabilityToStoreClientMock = $this->getAvailabilityToStoreClientMock();

        $availabilityToStoreClientMock->method('getCurrentStore')
            ->willReturn((new StoreTransfer())->setName($this->tester::DEFAULT_STORE_NAME));

        $availabilityStorageMock = $this->createAvailabilityStorageMock(
            [],
            $availabilityResourceKeyBuilderMock,
            $this->tester::DEFAULT_LOCALE_NAME,
            $availabilityToStoreClientMock,
        );

        $factoryMock = $this->getFactoryMock($availabilityStorageMock);
        // Act
        $actualProductAvailability = $this->getAvailabilityClientWithMockFactory($factoryMock)->findProductAvailabilityByIdProductAbstract(static::ID_PRODUCT_ABSTRACT);

        // Assert
        $this->assertSame(StorageAvailabilityTransfer::class, get_class($actualProductAvailability));
    }

    /**
     * @param array|null $returnedProductAvailability
     *
     * @return void
     */
    protected function setStorageReturn(?array $returnedProductAvailability): void
    {
        $availabilityToStorageBridge = $this->getMockBuilder(AvailabilityToStorageInterface::class)->getMock();
        $availabilityToStorageBridge->method('get')->willReturn($returnedProductAvailability);
        $this->tester->setDependency(AvailabilityDependencyProvider::KV_STORAGE, $availabilityToStorageBridge);
    }

    /**
     * @return \Spryker\Client\Availability\AvailabilityClientInterface
     */
    protected function createAvailabilityClient(): AvailabilityClientInterface
    {
        return new AvailabilityClient();
    }

    /**
     * @param \SprykerTest\Client\Availability\AvailabilityFactory $factoryMock
     *
     * @return \Spryker\Client\Availability\AvailabilityClient
     */
    protected function getAvailabilityClientWithMockFactory($factoryMock): AvailabilityClient
    {
        $availabilityClientMock = $this->getMockBuilder(AvailabilityClient::class)
            ->onlyMethods(['getFactory'])
            ->disableOriginalConstructor()
            ->getMock();

        $availabilityClientMock->expects($this->any())
            ->method('getFactory')
            ->will($this->returnValue($factoryMock));

        return $availabilityClientMock;
    }

    /**
     * @param \Spryker\Client\Availability\Storage\AvailabilityStorage $availabilityStorageMock
     *
     * @return \Spryker\Client\Availability\AvailabilityFactory
     */
    protected function getFactoryMock($availabilityStorageMock): AvailabilityFactory
    {
        $factoryMock = $this->getMockBuilder(AvailabilityFactory::class)
            ->onlyMethods(['createCurrentLocaleAvailabilityStorage'])
            ->disableOriginalConstructor()
            ->getMock();

        $factoryMock->method('createCurrentLocaleAvailabilityStorage')
            ->willReturn($availabilityStorageMock);

        return $factoryMock;
    }

    /**
     * @param array $storageReturn
     * @param $availabilityResourceKeyBuilderMock
     * @param $currentLocale
     * @param $availabilityToStoreClientMock
     *
     * @return \Spryker\Client\Availability\Storage\AvailabilityStorage
     */
    protected function createAvailabilityStorageMock(
        array $storageReturn = [],
        $availabilityResourceKeyBuilderMock = null,
        $currentLocale = 'en_US',
        $availabilityToStoreClientMock = null
    ): AvailabilityStorage {
        if ($availabilityResourceKeyBuilderMock === null) {
            $availabilityResourceKeyBuilderMock = $this->getAvailabilityResourceKeyBuilderMock();
        }

        if ($availabilityToStoreClientMock === null) {
            $availabilityToStoreClientMock = $this->getStorageClientMock($storageReturn);
        }
        $availabilityStorageMock = new AvailabilityStorage(
            $this->getStorageClientMock($storageReturn),
            $availabilityResourceKeyBuilderMock,
            $currentLocale,
            $availabilityToStoreClientMock,
        );

        return $availabilityStorageMock;
    }

    /**
     * @param array $storageReturn
     *
     * @return \Spryker\Client\Availability\Dependency\Client\AvailabilityToStorageInterface
     */
    protected function getStorageClientMock(array $storageReturn = []): AvailabilityToStorageInterface
    {
        $availabilityToStorageBridge = $this->getMockBuilder(AvailabilityToStorageInterface::class)->getMock();
        $availabilityToStorageBridge->method('get')->willReturn($storageReturn);

        return $availabilityToStorageBridge;
    }

    /**
     * @return \Spryker\Client\Availability\KeyBuilder\AvailabilityResourceKeyBuilder
     */
    protected function getAvailabilityResourceKeyBuilderMock(): AvailabilityResourceKeyBuilder
    {
        $availabilityResourceKeyBuilderMock = $this->getMockBuilder(AvailabilityResourceKeyBuilder::class)
            ->onlyMethods(['generateKey'])
            ->disableOriginalConstructor()
            ->getMock();

        return $availabilityResourceKeyBuilderMock;
    }

    /**
     * @return \Spryker\Client\Availability\Dependency\Client\AvailabilityToStoreClientInterface
     */
    protected function getAvailabilityToStoreClientMock(): AvailabilityToStoreClientInterface
    {
        $availabilityToStoreClientMock = $this->getMockBuilder(AvailabilityToStoreClientInterface::class)
            ->onlyMethods(['getCurrentStore'])
            ->disableOriginalConstructor()
            ->getMock();

        return $availabilityToStoreClientMock;
    }
}
