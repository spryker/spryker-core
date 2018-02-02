<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business\Model;

use Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermission;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\ProductCustomerPermission\ProductCustomerPermissionConfig;
use Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToTouchFacadeInterface;
use Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface;

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
     * @inheritdoc
     *
     * @param int $idCustomer
     * @param int $productId
     *
     * @return void
     */
    public function savePermission(int $idCustomer, int $productId)
    {
        $productCustomerPermissionEntity = new SpyProductCustomerPermission();
        $productCustomerPermissionEntity->setFkCustomer($idCustomer);
        $productCustomerPermissionEntity->setFkProductAbstract($productId);
        $productCustomerPermissionEntity->save();

        $this->touchEntity($productCustomerPermissionEntity->getPrimaryKey());
    }

    /**
     * @inheritdoc
     *
     * @param int $idCustomer
     * @param array $productIds
     *
     * @return void
     */
    public function savePermissions(int $idCustomer, array $productIds)
    {
        if (count($productIds) === 0) {
            return;
        }

        $existingRecords = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts($idCustomer, $productIds)
            ->find();
        $existingRecordIds = $this->getExistingRecordsIds($existingRecords);

        if ($existingRecords->count() === count($productIds)) {
            return;
        }

        $this->addNewProducts($idCustomer, $existingRecordIds, $productIds);
    }

    /**
     * @inheritdoc
     *
     * @param int $idCustomer
     * @param int $productId
     *
     * @return void
     */
    public function deletePermission(int $idCustomer, int $productId)
    {
        $productCustomerPermissionEntity = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts($idCustomer, [$productId])
            ->findOne();

        if ($productCustomerPermissionEntity) {
            $this->deleteAndCleanEntity($productCustomerPermissionEntity);
        }
    }

    /**
     * @inheritdoc
     *
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
            $this->deleteAndCleanEntity($productCustomerPermissionEntity);
        }
    }

    /**
     * @inheritdoc
     *
     * @param int $idCustomer
     * @param array $productIds
     *
     * @return void
     */
    public function deletePermissions(int $idCustomer, array $productIds)
    {
        $productCustomerPermissionEntities = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts($idCustomer, $productIds)
            ->find();

        foreach ($productCustomerPermissionEntities as $productCustomerPermissionEntity) {
            $this->deleteAndCleanEntity($productCustomerPermissionEntity);
        }
    }

    /**
     * @param \Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermission $productCustomerPermissionEntity
     *
     * @return void
     */
    protected function deleteAndCleanEntity(SpyProductCustomerPermission $productCustomerPermissionEntity)
    {
        $entityId = $productCustomerPermissionEntity->getIdProductCustomerPermission();
        $productCustomerPermissionEntity->delete();
        $this->touchFacade->touchDeleted(ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION, $entityId);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $existingRecords
     *
     * @return array
     */
    protected function getExistingRecordsIds(ObjectCollection $existingRecords)
    {
        $existingRecordIds = [];
        foreach ($existingRecords as $record) {
            $existingRecordIds[$record->getFkProductAbstract()] = $record->getIdProductCustomerPermission();
        }

        return $existingRecordIds;
    }

    /**
     * @param int $idCustomer
     * @param array $existingRecordIds
     * @param array $productIds
     *
     * @return void
     */
    protected function addNewProducts(int $idCustomer, array $existingRecordIds, array $productIds)
    {
        $productsToAdd = array_diff($productIds, array_keys($existingRecordIds));
        foreach ($productsToAdd as $productId) {
            $this->savePermission($idCustomer, $productId);
        }
    }

    /**
     * @param int $entityId
     *
     * @return void
     */
    protected function touchEntity(int $entityId)
    {
        $this->touchFacade->touchActive(ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION, $entityId);
    }
}
