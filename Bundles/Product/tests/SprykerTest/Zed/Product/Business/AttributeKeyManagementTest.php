<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Spryker\Zed\Product\Business\ProductFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group AttributeKeyManagementTest
 * Add your own group annotations below this line
 */
class AttributeKeyManagementTest extends Unit
{
    public const UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB = 'unique_attribute_key_not_existing_in_db';
    public const CHANGED_UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB = 'changed_unique_attribute_key_not_existing_in_db';

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacade
     */
    protected $productFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productFacade = new ProductFacade();
    }

    /**
     * @return void
     */
    public function testHasProductAttributeKeyReturnsFalseIfKeyDoesNotExist()
    {
        $result = $this->productFacade->hasProductAttributeKey(self::UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testHasProductAttributeKeyReturnsTrueIfKeyExists()
    {
        $productAttributeKeyEntity = $this->createAttributeKeyEntity(self::UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $result = $this->productFacade->hasProductAttributeKey($productAttributeKeyEntity->getKey());

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testGetProductAttributeKeyReturnsNullIfKeyDoesNotExist()
    {
        $productAttributeKeyTransfer = $this->productFacade->findProductAttributeKey(self::UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $this->assertNull($productAttributeKeyTransfer);
    }

    /**
     * @return void
     */
    public function testGetProductAttributeKeyReturnsTransferIfKeyExist()
    {
        $productAttributeKeyEntity = $this->createAttributeKeyEntity(self::UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $productAttributeKeyTransfer = $this->productFacade->findProductAttributeKey($productAttributeKeyEntity->getKey());

        $this->assertEquals($productAttributeKeyEntity->toArray(), $productAttributeKeyTransfer->toArray());
    }

    /**
     * @return void
     */
    public function testCreateProductAttributeKey()
    {
        $productAttributeKeyTransfer = new ProductAttributeKeyTransfer();
        $productAttributeKeyTransfer->setKey(self::UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $productAttributeKeyTransfer = $this->productFacade->createProductAttributeKey($productAttributeKeyTransfer);

        $this->assertNotNull($productAttributeKeyTransfer->getIdProductAttributeKey());
    }

    /**
     * @return void
     */
    public function testUpdateProductAttributeKey()
    {
        $productAttributeKeyEntity = $this->createAttributeKeyEntity(self::UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $productAttributeKeyTransfer = new ProductAttributeKeyTransfer();
        $productAttributeKeyTransfer
            ->setIdProductAttributeKey($productAttributeKeyEntity->getIdProductAttributeKey())
            ->setKey(self::CHANGED_UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $productAttributeKeyTransfer = $this->productFacade->updateProductAttributeKey($productAttributeKeyTransfer);

        $this->assertEquals(self::CHANGED_UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB, $productAttributeKeyTransfer->getKey());
    }

    /**
     * @param string $key
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKey
     */
    protected function createAttributeKeyEntity($key)
    {
        $productAttributeKeyEntity = new SpyProductAttributeKey();
        $productAttributeKeyEntity->setKey($key);
        $productAttributeKeyEntity->save();

        return $productAttributeKeyEntity;
    }
}
