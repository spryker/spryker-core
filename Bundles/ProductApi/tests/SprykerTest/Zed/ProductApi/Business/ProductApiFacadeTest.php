<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\ProductApi\Business\ProductApiFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductApi
 * @group Business
 * @group Facade
 * @group ProductApiFacadeTest
 * Add your own group annotations below this line
 */
class ProductApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductApi\ProductApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->productAbstractTransfer = $this->tester->haveProductAbstract();
    }

    /**
     * @return void
     */
    public function testGet()
    {
        $productApiFacade = new ProductApiFacade();

        $idProduct = $this->productAbstractTransfer->getIdProductAbstract();

        $resultTransfer = $productApiFacade->getProduct($idProduct);

        $this->assertInstanceOf(ApiItemTransfer::class, $resultTransfer);

        $id = $resultTransfer->getId();
        $this->assertNotEmpty($id);

        $newData = $resultTransfer->getData();
        $this->assertNotEmpty($newData['id_product_abstract']);
    }

    /**
     * @return void
     */
    public function testFind()
    {
        $productApiFacade = new ProductApiFacade();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiFilterTransfer = new ApiFilterTransfer();
        $apiRequestTransfer->setFilter($apiFilterTransfer);

        $resultTransfer = $productApiFacade->findProducts($apiRequestTransfer);

        $this->assertInstanceOf(ApiCollectionTransfer::class, $resultTransfer);
        $this->assertGreaterThan(1, count($resultTransfer->getData()));

        $data = $resultTransfer->getData();
        $this->assertNotEmpty($data[0]['id_product_abstract']);
    }

    /**
     * @return void
     */
    public function testFindConditionsAndSortAndLimit()
    {
        $productApiFacade = new ProductApiFacade();

        $jsonDataForPagination = $this->getJsonDataForPagination();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiFilterTransfer = new ApiFilterTransfer();
        $apiFilterTransfer->setCriteriaJson(json_encode($jsonDataForPagination));
        $apiFilterTransfer->setSort(['sku' => '-']);
        $apiFilterTransfer->setLimit(2);
        $apiFilterTransfer->setOffset(2);

        $apiRequestTransfer->setFilter($apiFilterTransfer);

        $resultTransfer = $productApiFacade->findProducts($apiRequestTransfer);

        $this->assertInstanceOf(ApiCollectionTransfer::class, $resultTransfer);
        $this->assertSame(2, count($resultTransfer->getData()));

        $data = $resultTransfer->getData();
        $this->assertGreaterThanOrEqual(209, $data[0]['sku']);
        $this->assertGreaterThanOrEqual(209, $data[1]['sku']);

        $apiPaginationTransfer = $resultTransfer->getPagination();
        $this->assertSame(2, $apiPaginationTransfer->getPage());
        $this->assertSame(2, $apiPaginationTransfer->getItemsPerPage());
        $this->assertGreaterThan(2, $apiPaginationTransfer->getTotal());
        $this->assertGreaterThan(2, $apiPaginationTransfer->getPageTotal());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionCode 404
     * @expectedExceptionMessage Out of bounds.
     *
     * @return void
     */
    public function testFindOutOfBounds()
    {
        $productApiFacade = new ProductApiFacade();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiFilterTransfer = new ApiFilterTransfer();
        $apiFilterTransfer->setLimit(20);
        $apiFilterTransfer->setOffset(9999);

        $apiRequestTransfer->setFilter($apiFilterTransfer);

        $productApiFacade->findProducts($apiRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAdd()
    {
        $productApiFacade = new ProductApiFacade();

        $apiDataTransfer = new ApiDataTransfer();
        $apiDataTransfer->setData([
            'sku' => 'sku' . time(),
            'attributes' => [],
            'product_concretes' => [],
            'id_tax_set' => 1,
        ]);

        $resultTransfer = $productApiFacade->addProduct($apiDataTransfer);

        $this->assertInstanceOf(ApiItemTransfer::class, $resultTransfer);

        $id = $resultTransfer->getId();
        $this->assertNotEmpty($id);

        $newData = $resultTransfer->getData();
        $this->assertNotEmpty($newData['id_product_abstract']);
    }

    /**
     * @return void
     */
    public function testEdit()
    {
        $productApiFacade = new ProductApiFacade();

        $apiDataTransfer = new ApiDataTransfer();
        $data = [
            'sku' => 'sku' . time() . 'new',
            'attributes' => [],
            'product_concretes' => [],
            'id_tax_set' => 1,
        ];
        $apiDataTransfer->setData($data);

        $idProduct = $this->productAbstractTransfer->getIdProductAbstract();
        $resultTransfer = $productApiFacade->updateProduct($idProduct, $apiDataTransfer);

        $this->assertInstanceOf(ApiItemTransfer::class, $resultTransfer);

        $id = $resultTransfer->getId();
        $this->assertNotEmpty($id);

        $newData = $resultTransfer->getData();
        $this->assertNotEmpty($newData['id_product_abstract']);
        $this->assertSame($data['sku'], $newData['sku']);
    }

    /**
     * @return void
     */
    public function testUpdate()
    {
        $productApiFacade = new ProductApiFacade();

        $idProductAbstract = $this->productAbstractTransfer->getIdProductAbstract();
        $apiDataTransfer = new ApiDataTransfer();
        $apiDataTransfer->setData([
            'id_product_abstract' => $this->productAbstractTransfer->getIdProductAbstract(),
            'sku' => 'sku' . time() . '-update',
            'attributes' => [],
            'product_concretes' => [],
            'id_tax_set' => 1,
        ]);

        $resultTransfer = $productApiFacade->updateProduct($idProductAbstract, $apiDataTransfer);

        $this->assertInstanceOf(ApiItemTransfer::class, $resultTransfer);
    }

    /**
     * @return array
     */
    protected function getJsonDataForPagination(): array
    {
        return [
            "rules" => [
                [
                    "id" => "spy_product_abstract.id_product_abstract",
                    "field" => "spy_product_abstract.id_product_abstract",
                    "type" => "number",
                    "input" => "text",
                    "operator" => "greater_or_equal",
                    "value" => "209",
                ],
            ],
        ];
    }
}
