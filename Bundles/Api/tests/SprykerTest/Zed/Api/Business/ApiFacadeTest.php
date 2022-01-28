<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Api\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Api
 * @group Business
 * @group Facade
 * @group Facade
 * @group ApiFacadeTest
 * Add your own group annotations below this line
 */
class ApiFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const KEY_DATA = 'data';

    /**
     * @var string
     */
    protected const KEY_NAME = 'name';

    /**
     * @var \SprykerTest\Zed\Api\ApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateApiCollectionWithItems(): void
    {
        // Arrange
        $apiItemTransfers = [
            (new ApiItemTransfer())->setData([static::KEY_NAME => 'random']),
            (new ApiItemTransfer())->setData([static::KEY_NAME => 'newer']),
        ];

        // Act
        $apiCollectionTransfer = $this->tester->getFacade()->createApiCollection($apiItemTransfers);

        // Assert
        $data = $apiCollectionTransfer->getData();

        $this->assertSame($data[0][static::KEY_DATA][static::KEY_NAME], 'random');
        $this->assertSame($data[1][static::KEY_DATA][static::KEY_NAME], 'newer');
    }

    /**
     * @return void
     */
    public function testCreateApiCollectionWithoutItems(): void
    {
        // Act
        $apiCollectionTransfer = $this->tester->getFacade()->createApiCollection([]);

        // Assert
        $this->assertIsArray($apiCollectionTransfer->getData());
        $this->assertEmpty($apiCollectionTransfer->getData());
    }

    /**
     * @return void
     */
    public function testCreateApiItem(): void
    {
        // Arrange
        $apiItemTransfer = (new ApiItemTransfer())->setData([static::KEY_NAME => 'hello']);

        // Act
        $apiItemTransfer = $this->tester->getFacade()->createApiItem($apiItemTransfer, '1');

        // Assert
        $this->assertSame('hello', $apiItemTransfer->getData()[static::KEY_DATA][static::KEY_NAME]);
        $this->assertSame('1', $apiItemTransfer->getId());
    }

    /**
     * @return void
     */
    public function testCreateApiItemWithoutItemDataAndId(): void
    {
        // Act
        $apiItemTransfer = $this->tester->getFacade()->createApiItem();

        // Assert
        $this->assertEmpty($apiItemTransfer->getData());
        $this->assertEmpty($apiItemTransfer->getId());
    }

    /**
     * @return void
     */
    public function testCreateApiItemWithoutItemData(): void
    {
        // Act
        $apiItemTransfer = $this->tester->getFacade()->createApiItem(null, '1');

        // Assert
        $this->assertEmpty($apiItemTransfer->getData());
        $this->assertSame('1', $apiItemTransfer->getId());
    }

    /**
     * @return void
     */
    public function testCreateApiItemWithoutItemId(): void
    {
        // Arrange
        $apiItemTransfer = (new ApiItemTransfer())->setData([static::KEY_NAME => 'hello']);

        // Act
        $apiItemTransfer = $this->tester->getFacade()->createApiItem($apiItemTransfer);

        // Assert
        $this->assertSame('hello', $apiItemTransfer->getData()[static::KEY_DATA][static::KEY_NAME]);
        $this->assertEmpty($apiItemTransfer->getId());
    }
}
