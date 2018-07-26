<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CategoriesRestApi\Processor;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapper;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group CategoriesRestApi
 * @group Processor
 * @group CategoriesRestApiResourceMapperTest
 * Add your own group annotations below this line
 */
class CategoriesRestApiResourceMapperTest extends Unit
{
    /**
     * @var \Generated\Shared\Transfer\RestCategoriesTreeTransfer
     */
    protected $restCategoriesTreeTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testMapperWillReturnRestResponseWithNotEmptyAttributesData(): void
    {
        $this->restCategoriesTreeTransfer = (new CategoriesResourceMapper())
            ->mapCategoriesResourceToRestCategoriesTransfer($this->mockCategoryClientResponseTransfer());

        $this->assertEquals('Test category', $this->restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getName());
        $this->assertEquals(90, $this->restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getOrder());
        $this->assertEquals(4, $this->restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getNodeId());
        $this->assertNotEmpty($this->restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getChildren());
        $this->assertEquals(2, $this->restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getChildren()->count());
    }

    /**
     * @return void
     */
    public function testMapperWillReturnRestResponseWithEmptyData(): void
    {
        $this->restCategoriesTreeTransfer = (new CategoriesResourceMapper())
            ->mapCategoriesResourceToRestCategoriesTransfer($this->mockCategoryClientEmptyResponseTransfer());

        $this->assertNull($this->restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getName());
        $this->assertNull($this->restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getOrder());
        $this->assertNull($this->restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getNodeId());
        $this->assertEmpty($this->restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getChildren());
        $this->assertEquals(0, $this->restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getChildren()->count());
    }

    /**
     * @return array
     */
    protected function mockCategoryClientEmptyResponseTransfer()
    {
        return [new CategoryNodeStorageTransfer()];
    }

    /**
     * @return array
     */
    protected function mockCategoryClientResponseTransfer()
    {
        return [$this->mockCategoryTransfer()];
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    protected function mockCategoryTransfer()
    {
        return (new CategoryNodeStorageTransfer())->fromArray([
            "node_id" => 4,
            "order" => 90,
            "name" => "Test category",
            "children" => $this->mockChildren(),
        ]);
    }

    /**
     * @return \ArrayObject
     */
    protected function mockChildren()
    {
        $children = new ArrayObject();

        $children->append([
            [
                "node_id" => 5,
                "order" => 30,
                "name" => "Test child category",
            ],
            [
                "node_id" => 4,
                "order" => 40,
                "name" => "Test second child category",
            ],
        ]);

        return $children;
    }
}
