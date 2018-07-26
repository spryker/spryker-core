<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CategoriesRestApi\Processor;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Spryker\Glue\CategoriesRestApi\CategoriesRestApiConfig;
use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientBridge;
use Spryker\Glue\CategoriesRestApi\Processor\Categories\CategoriesRestApiReader;
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapper;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group CategoriesRestApi
 * @group Processor
 * @group CategoriesRestApiResourceReaderTest
 * Add your own group annotations below this line
 */
class CategoriesRestApiResourceReaderTest extends Unit
{
    /**
     * @var \Spryker\Glue\CategoriesRestApi\Processor\Categories\CategoriesRestApiReader
     */
    protected $categoriesRestApiReader;

    /**
     * @var array
     */
    protected $categoryNodeData = [
        'id_category' => 30,
        'node_id' => 11,
        'name' => 'test',
        'is_active' => true,
        'children' => [],
        'parents' => [],
        'order' => 30,
        'meta_title' => 'Meta Title',
        'meta_keywords' => 'Meta Keywords',
        'meta_description' => 'Meta Description',
    ];

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
    public function testReadCategoriesTreeWillReturnValidRestResponseObject(): void
    {
        $deLocale = 'de_de';

        $this->createCategoryTreeReader();

        $categoriesTreeRestResponse = $this->categoriesRestApiReader->readCategoriesTree($deLocale);

        $this->assertInstanceOf('\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface', $categoriesTreeRestResponse);
        $this->assertEquals(CategoriesRestApiConfig::RESOURCE_CATEGORIES, $categoriesTreeRestResponse->getResources()[0]->getType());
        $this->assertNull($categoriesTreeRestResponse->getResources()[0]->getId());
        $this->assertNotEmpty($categoriesTreeRestResponse->getResources()[0]->getAttributes());
    }

    /**
     * @return void
     */
    public function testReadCategoriesNodeWillReturnValidRestResponseObject(): void
    {
        $deLocale = 'de_de';

        $this->createCategoryReader($this->getCategoryTransfer());

        $categoriesTreeRestResponse = $this->categoriesRestApiReader->readCategory($this->categoryNodeData['node_id'], $deLocale);

        $this->assertInstanceOf('\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface', $categoriesTreeRestResponse);
        $this->assertEmpty($categoriesTreeRestResponse->getErrors());
        $this->assertEquals(CategoriesRestApiConfig::RESOURCE_CATEGORY, $categoriesTreeRestResponse->getResources()[0]->getType());
        $this->assertEquals($this->categoryNodeData['node_id'], $categoriesTreeRestResponse->getResources()[0]->getId());
        $this->assertNotEmpty($categoriesTreeRestResponse->getResources()[0]->getAttributes());

        $categoryAttributes = $categoriesTreeRestResponse->getResources()[0]->getAttributes();
        $categoryExpectedAttributes = $this->getCategoryTransfer();
        $this->assertEquals($categoryAttributes->getNodeId(), $categoryExpectedAttributes->getNodeId());
        $this->assertEquals($categoryAttributes->getName(), $categoryExpectedAttributes->getName());
        $this->assertEquals($categoryAttributes->getMetaTitle(), $categoryExpectedAttributes->getMetaTitle());
        $this->assertEquals($categoryAttributes->getMetaKeywords(), $categoryExpectedAttributes->getMetaKeywords());
        $this->assertEquals($categoryAttributes->getMetaDescription(), $categoryExpectedAttributes->getMetaDescription());
        $this->assertEquals($categoryAttributes->getOrder(), $categoryExpectedAttributes->getOrder());
        $this->assertEquals($categoryAttributes->getIsActive(), $categoryExpectedAttributes->getIsActive());
        $this->assertArrayHasKey('children', $categoryExpectedAttributes);
        $this->assertArrayHasKey('parents', $categoryExpectedAttributes);
    }

    /**
     * @return void
     */
    public function testReadCategoriesNodeWillReturnValidRestResponseObjectWhenCategoryIsNotFound(): void
    {
        $deLocale = 'de_de';

        $this->createCategoryReader($this->getCategoryEmptyTransfer());

        $this->categoriesRestApiReader->readCategory($this->categoryNodeData['node_id'], $deLocale);
        $categoriesTreeRestResponse = $this->categoriesRestApiReader->readCategory($this->categoryNodeData['node_id'], $deLocale);

        $this->assertInstanceOf('\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface', $categoriesTreeRestResponse);
        $this->assertNotEmpty($categoriesTreeRestResponse->getErrors());
        $this->assertEmpty($categoriesTreeRestResponse->getResources());
    }

    /**
     * @return \ArrayObject
     */
    protected function getCategoryTree()
    {
        return new ArrayObject([$this->getCategoryTransfer()]);
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    protected function getCategoryTransfer()
    {
        return (new CategoryNodeStorageTransfer())
            ->fromArray($this->categoryNodeData);
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    protected function getCategoryEmptyTransfer()
    {
        return (new CategoryNodeStorageTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $returnTransfer
     *
     * @return void
     */
    protected function createCategoryReader(CategoryNodeStorageTransfer $returnTransfer)
    {
        $mockCategoryClientBridge = $this->createPartialMock(
            CategoriesRestApiToCategoryStorageClientBridge::class,
            ['getCategoryNodeById']
        );

        $mockCategoryClientBridge->method('getCategoryNodeById')
            ->willReturn(
                $returnTransfer
            );

        $this->categoriesRestApiReader = new CategoriesRestApiReader(
            new RestResourceBuilder(),
            $mockCategoryClientBridge,
            new CategoriesResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CategoriesRestApi\Processor\Categories\CategoriesRestApiReader
     */
    protected function createCategoryTreeReader()
    {
        $mockCategoryClientBridge = $this->createPartialMock(
            CategoriesRestApiToCategoryStorageClientBridge::class,
            ['getCategories']
        );
        $mockCategoryClientBridge->method('getCategories')
            ->willReturn(
                $this->getCategoryTree()
            );

        return $this->categoriesRestApiReader = new CategoriesRestApiReader(
            new RestResourceBuilder(),
            $mockCategoryClientBridge,
            new CategoriesResourceMapper()
        );
    }
}
