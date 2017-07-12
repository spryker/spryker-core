<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductManagement\Business;

use Codeception\TestCase\Test;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Spryker\Zed\ProductManagement\Business\ProductManagementFacade;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group ProductManagement
 * @group Business
 * @group ProductManagementFacadeAttributeTest
 */
class ProductManagementFacadeAttributeTest extends Test
{

    /**
     * @var \Spryker\Zed\ProductManagement\Business\ProductManagementFacade
     */
    protected $productManagementFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer
     */
    protected $productManagementQueryContainer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productManagementFacade = new ProductManagementFacade();
        $this->productManagementQueryContainer = new ProductManagementQueryContainer();
    }

    /**
     * @return void
     */
    public function testSuggestUnusedAttributeKeys()
    {
        $keys = [
            'some-unique-key-1',
            'some-unique-key-2',
            'some-unique-key-3',
            'other-unique-key-1',
            'other-unique-key-2',
            'other-unique-key-3',
        ];
        foreach ($keys as $key) {
            $productAttributeKeyEntity = new SpyProductAttributeKey();
            $productAttributeKeyEntity->setKey($key);
            $productAttributeKeyEntity->save();
        }

        $result = $this->productManagementFacade->suggestUnusedAttributeKeys('some-unique-key-', 5);

        $this->assertCount(3, $result);

        foreach ($result as $key) {
            $this->assertContains($key, $keys);
        }
    }

}
