<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\SalesProductClassTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspServiceConditionsTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Generated\Shared\Transfer\SspServicesSearchConditionTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Permission\SeeBusinessUnitOrdersPermissionPlugin;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\CompanySalesConnector\Communication\Plugin\Permission\SeeCompanyOrdersPermissionPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group Facade
 * @group GetSspServiceCollectionTest
 * Add your own group annotations below this line
 */
class GetSspServiceCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @uses \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig::getServiceProductClassName()
     *
     * @var string
     */
    protected const DEFAULT_PRODUCT_CLASS_NAME = 'Service';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester
     */
    protected SelfServicePortalBusinessTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderItemProductClassDatabaseTablesAreEmpty();

        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());
    }

    public function testGetSspServiceCollectionReturnsEmptyCollectionWhenNoServicesExist(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();

        $sspServiceCriteriaTransfer = new SspServiceCriteriaTransfer();
        $sspServiceCriteriaTransfer->setCompanyUser($companyUserTransfer);

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);

        // Assert
        $this->assertCount(0, $sspServiceCollectionTransfer->getServices());
    }

    public function testGetSspServiceCollectionReturnsService(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);

        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['process' => static::DEFAULT_OMS_PROCESS_NAME]);
        $idSalesOrderItem = $salesOrderItemEntity->getIdSalesOrderItem();

        $salesProductClassTransfer = $this->tester->haveSalesProductClass();
        $this->tester->haveSalesOrderItemToProductClass($idSalesOrderItem, $salesProductClassTransfer->getIdSalesProductClassOrFail());

        $sspServiceCriteriaTransfer = $this->createSspServiceCriteriaTransfer($companyUserTransfer);
        $sspServiceCriteriaTransfer->getServiceConditionsOrFail()->setProductClass($salesProductClassTransfer->getName());

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $sspServiceCollectionTransfer->getServices());
        $serviceTransfer = $sspServiceCollectionTransfer->getServices()->getIterator()->current();
        $this->assertSame($saveOrderTransfer->getOrderReference(), $serviceTransfer->getOrder()->getOrderReference());
    }

    public function testGetSspServiceCollectionReturnsEmptyCollectionWhenFilteredByNonExistingProductClass(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);

        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['process' => static::DEFAULT_OMS_PROCESS_NAME]);
        $idSalesOrderItem = $salesOrderItemEntity->getIdSalesOrderItem();

        $salesProductClassTransfer = $this->tester->haveSalesProductClass();
        $this->tester->haveSalesOrderItemToProductClass($idSalesOrderItem, $salesProductClassTransfer->getIdSalesProductClassOrFail());

        $sspServiceCriteriaTransfer = $this->createSspServiceCriteriaTransfer($companyUserTransfer);
        $sspServiceCriteriaTransfer->getServiceConditionsOrFail()->setProductClass('non-existing-product-class');

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);

        // Assert
        $this->assertCount(0, $sspServiceCollectionTransfer->getServices());
    }

    public function testGetSspServiceCollectionReturnsFilteredServicesByProductName(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['name' => 'product-to-find', 'process' => static::DEFAULT_OMS_PROCESS_NAME]);
        $salesOrderItemEntityTwo = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['name' => 'another-product', 'process' => static::DEFAULT_OMS_PROCESS_NAME]);

        $salesProductClassTransfer = $this->tester->haveSalesProductClass([
            SalesProductClassTransfer::NAME => static::DEFAULT_PRODUCT_CLASS_NAME,
        ]);
        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntity->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());
        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntityTwo->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());

        $sspServiceCriteriaTransfer = $this->createSspServiceCriteriaTransfer($companyUserTransfer);
        $sspServiceCriteriaTransfer->getServiceConditionsOrFail()->setServicesSearchCondition(
            (new SspServicesSearchConditionTransfer())->setProductName('find'),
        );

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $sspServiceCollectionTransfer->getServices());
        $this->assertSame('product-to-find', $sspServiceCollectionTransfer->getServices()->getIterator()->current()->getProductName());
    }

    public function testGetSspServiceCollectionReturnsFilteredServicesBySku(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['sku' => 'sku-to-find-123', 'process' => static::DEFAULT_OMS_PROCESS_NAME]);
        $salesOrderItemEntityTwo = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['sku' => 'another-sku-456', 'process' => static::DEFAULT_OMS_PROCESS_NAME]);

        $salesProductClassTransfer = $this->tester->haveSalesProductClass([
            SalesProductClassTransfer::NAME => static::DEFAULT_PRODUCT_CLASS_NAME,
        ]);
        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntity->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());
        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntityTwo->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());

        $sspServiceCriteriaTransfer = $this->createSspServiceCriteriaTransfer($companyUserTransfer);
        $sspServiceCriteriaTransfer->getServiceConditionsOrFail()->setServicesSearchCondition(
            (new SspServicesSearchConditionTransfer())->setSku('find-123'),
        );

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $sspServiceCollectionTransfer->getServices());
    }

    public function testGetSspServiceCollectionReturnsFilteredServicesByOrderReference(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $saveOrderTransferToFind = $this->haveCompanyUserOrder($companyUserTransfer);

        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransferToFind->getIdSalesOrderOrFail(), ['process' => static::DEFAULT_OMS_PROCESS_NAME]);

        $salesProductClassTransfer = $this->tester->haveSalesProductClass([
            SalesProductClassTransfer::NAME => static::DEFAULT_PRODUCT_CLASS_NAME,
        ]);

        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntity->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());

        $sspServiceCriteriaTransfer = $this->createSspServiceCriteriaTransfer($companyUserTransfer);
        $sspServiceCriteriaTransfer->getServiceConditionsOrFail()->setServicesSearchCondition(
            (new SspServicesSearchConditionTransfer())->setOrderReference($saveOrderTransferToFind->getOrderReference()),
        );

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $sspServiceCollectionTransfer->getServices());
        $this->assertSame($saveOrderTransferToFind->getOrderReference(), $sspServiceCollectionTransfer->getServices()->getIterator()->current()->getOrder()->getOrderReference());
    }

    public function testGetSspServiceCollectionReturnsFilteredServicesByCustomerReference(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $saveOrderTransferToFind = $this->haveCompanyUserOrder($companyUserTransfer);

        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransferToFind->getIdSalesOrderOrFail(), ['process' => static::DEFAULT_OMS_PROCESS_NAME]);

        $salesProductClassTransfer = $this->tester->haveSalesProductClass([
            SalesProductClassTransfer::NAME => static::DEFAULT_PRODUCT_CLASS_NAME,
        ]);

        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntity->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());

        $sspServiceCriteriaTransfer = $this->createSspServiceCriteriaTransfer($companyUserTransfer);
        $sspServiceCriteriaTransfer->getServiceConditionsOrFail()->setCustomerReference(
            $companyUserTransfer->getCustomerOrFail()->getCustomerReferenceOrFail(),
        );

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $sspServiceCollectionTransfer->getServices());
    }

    public function testGetSspServiceCollectionReturnsFilteredServicesBySspAssetReference(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['process' => static::DEFAULT_OMS_PROCESS_NAME]);

        $salesProductClassTransfer = $this->tester->haveSalesProductClass([
            SalesProductClassTransfer::NAME => static::DEFAULT_PRODUCT_CLASS_NAME,
        ]);

        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntity->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());

        $sspAssetTransfer = $this->tester->haveAsset();
        $this->tester->haveSalesSspAsset($saveOrderTransfer->getIdSalesOrder(), $sspAssetTransfer->getIdSspAssetOrFail());

        $sspServiceCriteriaTransfer = $this->createSspServiceCriteriaTransfer($companyUserTransfer);
        $sspServiceCriteriaTransfer->getServiceConditionsOrFail()->setSspAssetReferences([$sspAssetTransfer->getReferenceOrFail()]);

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $sspServiceCollectionTransfer->getServices());
        $this->assertSame(
            $sspAssetTransfer->getReferenceOrFail(),
            $sspServiceCollectionTransfer->getServices()->getIterator()->current()->getSspAssets()
                ->getIterator()
                ->current()
                ->getReference(),
        );
    }

    public function testGetSspServiceCollectionReturnsServicesForUserWithoutCompanyPermissions(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions(false);
        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['process' => static::DEFAULT_OMS_PROCESS_NAME]);

        $salesProductClassTransfer = $this->tester->haveSalesProductClass([
            SalesProductClassTransfer::NAME => static::DEFAULT_PRODUCT_CLASS_NAME,
        ]);

        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntity->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());

        $sspServiceCriteriaTransfer = $this->createSspServiceCriteriaTransfer($companyUserTransfer);

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);
        $orderTransfer = $sspServiceCollectionTransfer->getServices()->getIterator()->current()->getOrder();

        // Assert
        $this->assertCount(1, $sspServiceCollectionTransfer->getServices());
        $this->assertSame($saveOrderTransfer->getOrderReference(), $orderTransfer->getOrderReference());
    }

    public function testGetSspServiceCollectionReturnsServicesForUserWithBusinessUnitPermissions(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $seeBusinessUnitOrdersPermissionTransfer = $this->tester->havePermission(new SeeBusinessUnitOrdersPermissionPlugin());

        $permissionCollectionTransfer = (new PermissionCollectionTransfer())
            ->addPermission($seeBusinessUnitOrdersPermissionTransfer);

        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions(
            $companyTransfer,
            $permissionCollectionTransfer,
        );

        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['process' => static::DEFAULT_OMS_PROCESS_NAME]);

        $anotherCompanyTransfer = $this->tester->haveCompany();
        $anotherCompanyUserTransfer = $this->tester->haveCompanyUserWithPermissions(
            $anotherCompanyTransfer,
            new PermissionCollectionTransfer(),
        );

        $anotherSaveOrderTransfer = $this->haveCompanyUserOrder($anotherCompanyUserTransfer);
        $salesOrderItemEntityTwo = $this->tester->createSalesOrderItemForOrder($anotherSaveOrderTransfer->getIdSalesOrderOrFail(), ['process' => static::DEFAULT_OMS_PROCESS_NAME]);

        $salesProductClassTransfer = $this->tester->haveSalesProductClass([
            SalesProductClassTransfer::NAME => static::DEFAULT_PRODUCT_CLASS_NAME,
        ]);

        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntity->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());
        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntityTwo->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());

        $sspServiceCriteriaTransfer = $this->createSspServiceCriteriaTransfer($companyUserTransfer);

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $sspServiceCollectionTransfer->getServices());
        $this->assertSame($saveOrderTransfer->getOrderReference(), $sspServiceCollectionTransfer->getServices()->getIterator()->current()->getOrder()->getOrderReference());
    }

    public function testGetSspServiceCollectionReturnsServicesForUserWithCompanyPermissions(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $seeCompanyOrdersPermissionTransfer = $this->tester->havePermission(new SeeCompanyOrdersPermissionPlugin());

        $permissionCollectionTransfer = (new PermissionCollectionTransfer())
            ->addPermission($seeCompanyOrdersPermissionTransfer);

        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions(
            $companyTransfer,
            $permissionCollectionTransfer,
        );

        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);
        $salesOrderItemEntity = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['process' => static::DEFAULT_OMS_PROCESS_NAME]);

        $anotherCompanyTransfer = $this->tester->haveCompany();
        $anotherCompanyUserTransfer = $this->tester->haveCompanyUserWithPermissions(
            $anotherCompanyTransfer,
            new PermissionCollectionTransfer(),
        );

        $anotherSaveOrderTransfer = $this->haveCompanyUserOrder($anotherCompanyUserTransfer);
        $salesOrderItemEntityTwo = $this->tester->createSalesOrderItemForOrder($anotherSaveOrderTransfer->getIdSalesOrderOrFail(), ['process' => static::DEFAULT_OMS_PROCESS_NAME]);

        $salesProductClassTransfer = $this->tester->haveSalesProductClass([
            SalesProductClassTransfer::NAME => static::DEFAULT_PRODUCT_CLASS_NAME,
        ]);

        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntity->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());
        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntityTwo->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());

        $sspServiceCriteriaTransfer = $this->createSspServiceCriteriaTransfer($companyUserTransfer);

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $sspServiceCollectionTransfer->getServices());
        $this->assertSame($saveOrderTransfer->getOrderReference(), $sspServiceCollectionTransfer->getServices()->getIterator()->current()->getOrder()->getOrderReference());
    }

    public function testGetSspServiceCollectionReturnsSortedServices(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);

        $salesOrderItemEntityA = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['name' => 'A Product', 'process' => static::DEFAULT_OMS_PROCESS_NAME]);
        $salesOrderItemEntityB = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['name' => 'B Product', 'process' => static::DEFAULT_OMS_PROCESS_NAME]);

        $salesProductClassTransfer = $this->tester->haveSalesProductClass([SalesProductClassTransfer::NAME => static::DEFAULT_PRODUCT_CLASS_NAME]);
        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntityA->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());
        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntityB->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());

        $sspServiceCriteriaTransfer = $this->createSspServiceCriteriaTransfer($companyUserTransfer);
        $sortTransfer = (new SortTransfer())
            ->setField('product_name')
            ->setDirection('DESC');
        $sspServiceCriteriaTransfer->addSort($sortTransfer);

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);
        $services = $sspServiceCollectionTransfer->getServices();

        // Assert
        $this->assertCount(2, $services);
        $this->assertSame('B Product', $services[0]->getProductName());
        $this->assertSame('A Product', $services[1]->getProductName());
    }

    public function testGetSspServiceCollectionReturnsPaginatedServices(): void
    {
        // Arrange
        $companyUserTransfer = $this->haveCompanyWithUserWithPermissions();
        $saveOrderTransfer = $this->haveCompanyUserOrder($companyUserTransfer);

        $salesProductClassTransfer = $this->tester->haveSalesProductClass([SalesProductClassTransfer::NAME => static::DEFAULT_PRODUCT_CLASS_NAME]);

        $salesOrderItemEntity1 = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['name' => 'Product 1', 'process' => static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntity1->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());
        $salesOrderItemEntity2 = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['name' => 'Product 2', 'process' => static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntity2->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());
        $salesOrderItemEntity3 = $this->tester->createSalesOrderItemForOrder($saveOrderTransfer->getIdSalesOrderOrFail(), ['name' => 'Product 3', 'process' => static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->haveSalesOrderItemToProductClass($salesOrderItemEntity3->getIdSalesOrderItem(), $salesProductClassTransfer->getIdSalesProductClassOrFail());

        $sspServiceCriteriaTransfer = $this->createSspServiceCriteriaTransfer($companyUserTransfer);
        $paginationTransfer = (new PaginationTransfer())
            ->setPage(2)
            ->setMaxPerPage(1);
        $sspServiceCriteriaTransfer->setPagination($paginationTransfer);
        $sspServiceCriteriaTransfer->addSort((new SortTransfer())->setField('product_name')->setDirection('ASC'));

        // Act
        $sspServiceCollectionTransfer = $this->tester->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);

        // Assert
        $this->assertCount(1, $sspServiceCollectionTransfer->getServices());
        $this->assertSame('Product 2', $sspServiceCollectionTransfer->getServices()->getIterator()->current()->getProductName());

        $responsePaginationTransfer = $sspServiceCollectionTransfer->getPagination();
        $this->assertNotNull($responsePaginationTransfer);
        $this->assertSame(3, $responsePaginationTransfer->getNbResults());
        $this->assertSame(2, $responsePaginationTransfer->getPage());
        $this->assertSame(1, $responsePaginationTransfer->getMaxPerPage());
        $this->assertSame(3, $responsePaginationTransfer->getLastPage());
    }

    protected function createSspServiceCriteriaTransfer(CompanyUserTransfer $companyUserTransfer): SspServiceCriteriaTransfer
    {
        return (new SspServiceCriteriaTransfer())
            ->setCompanyUser($companyUserTransfer)
            ->setServiceConditions(new SspServiceConditionsTransfer());
    }

    protected function haveCompanyWithUserWithPermissions(bool $withPermissions = true): CompanyUserTransfer
    {
        $companyTransfer = $this->tester->haveCompany();

        if ($withPermissions) {
            $seeCompanyOrdersPermissionTransfer = $this->tester->havePermission(new SeeCompanyOrdersPermissionPlugin());
            $seeBusinessUnitOrdersPermissionTransfer = $this->tester->havePermission(new SeeBusinessUnitOrdersPermissionPlugin());

            $permissionCollectionTransfer = (new PermissionCollectionTransfer())
                ->addPermission($seeCompanyOrdersPermissionTransfer)
                ->addPermission($seeBusinessUnitOrdersPermissionTransfer);
        }

        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions(
            $companyTransfer,
            $permissionCollectionTransfer ?? new PermissionCollectionTransfer(),
        );

        return $companyUserTransfer;
    }

    protected function haveCompanyUserOrder(CompanyUserTransfer $companyUserTransfer): SaveOrderTransfer
    {
        $saveOrderTransfer = $this->tester->haveOrder($companyUserTransfer->toArray(), static::DEFAULT_OMS_PROCESS_NAME);
        $spySalesOrder = SpySalesOrderQuery::create()->findPk($saveOrderTransfer->getIdSalesOrderOrFail());
        $spySalesOrder->setCompanyBusinessUnitUuid($companyUserTransfer->getCompanyBusinessUnit()->getUuid());
        $spySalesOrder->setCompanyUuid($companyUserTransfer->getCompany()->getUuid());
        $spySalesOrder->setCustomerReference($companyUserTransfer->getCustomer()->getCustomerReference());
        $spySalesOrder->save();

        return $saveOrderTransfer;
    }
}
