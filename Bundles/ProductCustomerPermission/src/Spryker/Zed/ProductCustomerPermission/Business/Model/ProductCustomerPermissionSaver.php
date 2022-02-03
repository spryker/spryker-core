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
    public function savePermission(int $idCustomer, int $idProductAbstract): void
    {
        $productCustomerPermissionEntity = new SpyProductCustomerPermission();
        $productCustomerPermissionEntity
            ->setFkCustomer($idCustomer)
            ->setFkProductAbstract($idProductAbstract)
            ->save();

        $this->touchFacade->touchActive(
            ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION,
            $productCustomerPermissionEntity->getIdProductCustomerPermission(),
        );
    }

    /**
     * @param int $idCustomer
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function savePermissions(int $idCustomer, array $productAbstractIds): void
    {
        if (count($productAbstractIds) === 0) {
            return;
        }

        $existingPermissionEntityCollection = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts($idCustomer, $productAbstractIds)
            ->find();
        $existingIdProductCustomerPermissions = $this->getExistingIdProductCustomerPermissions($existingPermissionEntityCollection);

        if ($existingPermissionEntityCollection->count() === count($productAbstractIds)) {
            return;
        }

        $this->addNewProductPermissions($idCustomer, $existingIdProductCustomerPermissions, $productAbstractIds);
    }

    /**
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deletePermission(int $idCustomer, int $idProductAbstract): void
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
    public function deleteAllPermissions(int $idCustomer): void
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
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function deletePermissions(int $idCustomer, array $productAbstractIds): void
    {
        $productCustomerPermissionEntities = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts($idCustomer, $productAbstractIds)
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
    protected function deleteEntity(SpyProductCustomerPermission $productCustomerPermissionEntity): void
    {
        $idProductCustomerPermission = $productCustomerPermissionEntity->getIdProductCustomerPermission();
        $productCustomerPermissionEntity->delete();
        $this->touchFacade->touchDeleted(ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION, $idProductCustomerPermission);
    }

    /**
     * @param \Traversable<\Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermission> $existingEntities
     *
     * @return array<int>
     */
    protected function getExistingIdProductCustomerPermissions(Traversable $existingEntities): array
    {
        $existingIdProductCustomerPermissions = [];
        foreach ($existingEntities as $existingEntity) {
            $existingIdProductCustomerPermissions[$existingEntity->getFkProductAbstract()] = $existingEntity->getIdProductCustomerPermission();
        }

        return $existingIdProductCustomerPermissions;
    }

    /**
     * @param int $idCustomer
     * @param array<int> $existingIdProductCustomerPermissions : as a key idProductAbstract
     * @param array<int> $idProductAbstracts
     *
     * @return void
     */
    protected function addNewProductPermissions(int $idCustomer, array $existingIdProductCustomerPermissions, array $idProductAbstracts): void
    {
        $productsToAdd = array_diff($idProductAbstracts, array_keys($existingIdProductCustomerPermissions));
        foreach ($productsToAdd as $idProductAbstract) {
            $this->savePermission($idCustomer, $idProductAbstract);
        }
    }
}
