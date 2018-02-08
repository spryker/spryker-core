<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business\Model;

use Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermission;
use Spryker\Shared\ProductCustomerPermission\ProductCustomerPermissionConfig;
use Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToTouchFacadeInterface;
use Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface;
use Traversable;

class ProductCustomerPermissionSaver implements ProductCustomerPermissionSaverInterface
{
    /**
     * @var \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToTouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToTouchFacadeInterface $touchFacade
     */
    public function __construct(
        ProductCustomerPermissionQueryContainerInterface $queryContainer,
        ProductCustomerPermissionToTouchFacadeInterface $touchFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function savePermission(int $idCustomer, int $idProductAbstract)
    {
        $productCustomerPermissionEntity = new SpyProductCustomerPermission();
        $productCustomerPermissionEntity
            ->setFkCustomer($idCustomer)
            ->setFkProductAbstract($idProductAbstract)
            ->save();

        $this->touchFacade->touchActive(
            ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION,
            $productCustomerPermissionEntity->getIdProductCustomerPermission()
        );
    }

    /**
     * @param int $idCustomer
     * @param int[] $idProducts
     *
     * @return void
     */
    public function savePermissions(int $idCustomer, array $idProducts)
    {
        if (count($idProducts) === 0) {
            return;
        }

        $existingPermissionEntityCollection = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts($idCustomer, $idProducts)
            ->find();
        $idExistingRecords = $this->getExistingIdProductCustomerPermissionCollection($existingPermissionEntityCollection);

        if ($existingPermissionEntityCollection->count() === count($idProducts)) {
            return;
        }

        $this->addNewProductPermissions($idCustomer, $idExistingRecords, $idProducts);
    }

    /**
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deletePermission(int $idCustomer, int $idProductAbstract)
    {
        $productCustomerPermissionEntity = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts($idCustomer, [$idProductAbstract])
            ->findOne();

        if ($productCustomerPermissionEntity) {
            $this->deleteEntity($productCustomerPermissionEntity);
        }
    }

    /**
     * @param int $idCustomer
     *
     * @return void
     */
    public function deleteAllPermissions(int $idCustomer)
    {
        $productCustomerPermissionEntities = $this->queryContainer
            ->queryProductCustomerPermissionByCustomer($idCustomer)
            ->find();

        foreach ($productCustomerPermissionEntities as $productCustomerPermissionEntity) {
            $this->deleteEntity($productCustomerPermissionEntity);
        }
    }

    /**
     * @param int $idCustomer
     * @param int[] $idProducts
     *
     * @return void
     */
    public function deletePermissions(int $idCustomer, array $idProducts)
    {
        $productCustomerPermissionEntities = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts($idCustomer, $idProducts)
            ->find();

        foreach ($productCustomerPermissionEntities as $productCustomerPermissionEntity) {
            $this->deleteEntity($productCustomerPermissionEntity);
        }
    }

    /**
     * @param \Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermission $productCustomerPermissionEntity
     *
     * @return void
     */
    protected function deleteEntity(SpyProductCustomerPermission $productCustomerPermissionEntity)
    {
        $idProductCustomerPermission = $productCustomerPermissionEntity->getIdProductCustomerPermission();
        $productCustomerPermissionEntity->delete();
        $this->touchFacade->touchDeleted(ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION, $idProductCustomerPermission);
    }

    /**
     * @param \Traversable $existingEntities
     *
     * @return int[]
     */
    protected function getExistingIdProductCustomerPermissionCollection(Traversable $existingEntities)
    {
        $idExistingRecords = [];
        foreach ($existingEntities as $record) {
            $idExistingRecords[$record->getFkProductAbstract()] = $record->getIdProductCustomerPermission();
        }

        return $idExistingRecords;
    }

    /**
     * @param int $idCustomer
     * @param array $idExistingRecords
     * @param array $idProducts
     *
     * @return void
     */
    protected function addNewProductPermissions(int $idCustomer, array $idExistingRecords, array $idProducts)
    {
        $productsToAdd = array_diff($idProducts, array_keys($idExistingRecords));
        foreach ($productsToAdd as $idProductAbstract) {
            $this->savePermission($idCustomer, $idProductAbstract);
        }
    }
}
