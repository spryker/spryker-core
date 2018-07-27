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
     * @var array
     */
    protected $categoryTreeData = [
        "node_id" => 4,
        "order" => 90,
        "name" => "Test category",
    ];

    /**
     * @var array
     */
    protected $categoryChildrenNodes = [
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
    ];

    /**
     * @return void
     */
    protected function setUp()
    {
        if (empty($this->categoryTreeData['children'])) {
            $this->categoryTreeData['children'] = $this->mockChildren();
        }

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testMapperWillReturnRestResponseWithNotEmptyAttributesData(): void
    {
        $restCategoriesTreeTransfer = (new CategoriesResourceMapper())
            ->mapCategoriesResourceToRestCategoriesTransfer($this->mockCategoryClientResponseTransfer());

        $this->assertEquals($this->categoryTreeData['name'], $restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getName());
        $this->assertEquals($this->categoryTreeData['order'], $restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getOrder());
        $this->assertEquals($this->categoryTreeData['node_id'], $restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getNodeId());
        $this->assertNotEmpty($restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getChildren());
        $this->assertEquals($this->categoryTreeData['children']->count(), $restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getChildren()->count());
    }

    /**
     * @return void
     */
    public function testMapperWillReturnRestResponseWithEmptyData(): void
    {
        $restCategoriesTreeTransfer = (new CategoriesResourceMapper())
            ->mapCategoriesResourceToRestCategoriesTransfer($this->mockCategoryClientEmptyResponseTransfer());

        $this->assertNull($restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getName());
        $this->assertNull($restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getOrder());
        $this->assertNull($restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getNodeId());
        $this->assertEmpty($restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getChildren());
        $this->assertEquals(0, $restCategoriesTreeTransfer->getCategoryNodesStorage()[0]->getChildren()->count());
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
        return (new CategoryNodeStorageTransfer())->fromArray($this->categoryTreeData);
    }

    /**
     * @return \ArrayObject
     */
    protected function mockChildren()
    {
        $children = new ArrayObject();

        $children->append($this->categoryChildrenNodes[0]);

        return $children;
    }
}
