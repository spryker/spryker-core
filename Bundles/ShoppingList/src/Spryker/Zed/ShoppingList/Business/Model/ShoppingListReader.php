<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Shared\ShoppingList\ShoppingListConfig;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListReader implements ShoppingListReaderInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface
     */
    protected $shoppingListRepository;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @var \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface
     */
    protected $pluginExecutor;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface $customerFacade
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface $pluginExecutor
     */
    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListToProductFacadeInterface $productFacade,
        ShoppingListToCompanyUserFacadeInterface $customerFacade,
        ShoppingListItemPluginExecutorInterface $pluginExecutor
    ) {
        $this->shoppingListRepository = $shoppingListRepository;
        $this->productFacade = $productFacade;
        $this->companyUserFacade = $customerFacade;
        $this->pluginExecutor = $pluginExecutor;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListTransfer = $this->shoppingListRepository->findShoppingListById($shoppingListTransfer);

        if (!$shoppingListTransfer || !$this->checkReadPermission($shoppingListTransfer)) {
            return new ShoppingListTransfer();
        }

        $shoppingListCompanyBusinessUnits = $this->shoppingListRepository
            ->getShoppingListCompanyBusinessUnitsByShoppingListId($shoppingListTransfer)
            ->getShoppingListCompanyBusinessUnits();

        $shoppingListCompanyUsers = $this->shoppingListRepository
            ->getShoppingListCompanyUsersByShoppingListId($shoppingListTransfer)
            ->getShoppingListCompanyUsers();

        $shoppingListTransfer
            ->setSharedCompanyUsers($shoppingListCompanyUsers)
            ->setSharedCompanyBusinessUnits($shoppingListCompanyBusinessUnits);

        $shoppingListItemCollectionTransfer = $this->shoppingListRepository->findShoppingListItemsByIdShoppingList($shoppingListTransfer->getIdShoppingList());
        $shoppingListItemCollectionTransfer = $this->expandProducts($shoppingListItemCollectionTransfer);
        $shoppingListTransfer->setItems($shoppingListItemCollectionTransfer->getItems());

        return $shoppingListTransfer;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    public function getShoppingListOverview(ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer): ShoppingListOverviewResponseTransfer
    {
        $shoppingListOverviewRequestTransfer->requireShoppingList();
        $shoppingListOverviewRequestTransfer->getShoppingList()->requireIdShoppingList();

        $shoppingListOverviewResponseTransfer = (new ShoppingListOverviewResponseTransfer())
            ->setShoppingList($shoppingListOverviewRequestTransfer->getShoppingList());

        $shoppingListTransfer = $this->getShoppingList($shoppingListOverviewRequestTransfer->getShoppingList());

        if (!$shoppingListTransfer->getIdShoppingList()) {
            return $shoppingListOverviewResponseTransfer;
        }

        $shoppingListOverviewRequestTransfer->setShoppingList($shoppingListTransfer);
        $shoppingListOverviewResponseTransfer = $this->shoppingListRepository->findShoppingListPaginatedItems($shoppingListOverviewRequestTransfer);
        $this->expandProducts($shoppingListOverviewResponseTransfer->getItemsCollection());

        $customerTransfer = new CustomerTransfer();
        $requestCompanyUserTransfer = $this->companyUserFacade->getCompanyUserById($shoppingListOverviewRequestTransfer->getShoppingList()->getIdCompanyUser());

        $customerTransfer->setCustomerReference($requestCompanyUserTransfer->getCustomer()->getCustomerReference());
        $customerTransfer->setCompanyUserTransfer($requestCompanyUserTransfer);

        $shoppingListOverviewResponseTransfer->setShoppingList($shoppingListTransfer);
        $shoppingListOverviewResponseTransfer->setShoppingLists($this->getCustomerShoppingListCollection($customerTransfer));

        return $shoppingListOverviewResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(CustomerTransfer $customerTransfer): ShoppingListCollectionTransfer
    {
        $customerReference = $customerTransfer
            ->requireCustomerReference()
            ->getCustomerReference();

        $customerOwnShoppingLists = $this->getCustomerShoppingListCollectionByReference($customerReference);
        $customerSharedShoppingLists = new ShoppingListCollectionTransfer();
        $businessUnitSharedShoppingLists = new ShoppingListCollectionTransfer();

        if ($customerTransfer->getCompanyUserTransfer() && $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser()) {
            $customerSharedShoppingLists = $this->shoppingListRepository->findCompanyUserSharedShoppingLists(
                $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser()
            );

            $businessUnitSharedShoppingLists = $this->shoppingListRepository->findCompanyBusinessUnitSharedShoppingLists(
                $customerTransfer->getCompanyUserTransfer()->getFkCompanyBusinessUnit()
            );

            $businessUnitSharedShoppingLists = $this->filterBlacklistedShoppingLists(
                $businessUnitSharedShoppingLists,
                $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser()
            );
        }

        return $this->mergeShoppingListCollections($customerOwnShoppingLists, $customerSharedShoppingLists, $businessUnitSharedShoppingLists);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function getShoppingListItemCollection(ShoppingListCollectionTransfer $shoppingListCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        $shoppingListIds = [];

        foreach ($shoppingListCollectionTransfer->getShoppingLists() as $shoppingList) {
            if ($this->checkReadPermission($shoppingList)) {
                $shoppingListIds[] = $shoppingList->getIdShoppingList();
            }
        }

        $shoppingListItemCollectionTransfer = $this->shoppingListRepository->findCustomerShoppingListsItemsByIds($shoppingListIds);

        return $this->expandProducts($shoppingListItemCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function getShoppingListItemCollectionTransfer(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        $shoppingListItemIds = [];

        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemIds[] = $shoppingListItemTransfer->getIdShoppingListItem();
        }

        $shoppingListItemCollectionTransfer = $this->shoppingListRepository->findShoppingListItemsByIds($shoppingListItemIds);

        return $this->expandProducts($shoppingListItemCollectionTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer
     */
    public function getShoppingListPermissionGroups(): ShoppingListPermissionGroupCollectionTransfer
    {
        return $this->shoppingListRepository->getShoppingListPermissionGroups();
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findCompanyUserPermissions(int $idCompanyUser): PermissionCollectionTransfer
    {
        $companyUserTransfer = $this->companyUserFacade->getCompanyUserById($idCompanyUser);
        $companyUserPermissionCollectionTransfer = new PermissionCollectionTransfer();

        $companyUserOwnShoppingListIds = $this->findCompanyUserShoppingListIds($companyUserTransfer);
        $companyBusinessUnitSharedShoppingListIds = $this->shoppingListRepository->findCompanyBusinessUnitSharedShoppingListsIds($companyUserTransfer->getFkCompanyBusinessUnit());
        $companyBusinessUnitBlacklistedShoppingListIds = $this->shoppingListRepository->getBlacklistedShoppingListIdsByIdCompanyUser($companyUserTransfer->getIdCompanyUser());
        $companyBusinessUnitSharedShoppingListIds = array_diff($companyBusinessUnitSharedShoppingListIds, $companyBusinessUnitBlacklistedShoppingListIds);

        $companyUserPermissionCollectionTransfer = $this->addReadPermissionToPermissionCollectionTransfer(
            $companyUserPermissionCollectionTransfer,
            array_merge(
                $this->shoppingListRepository->findCompanyUserSharedShoppingListsIds($companyUserTransfer->getIdCompanyUser()),
                $companyBusinessUnitSharedShoppingListIds,
                $companyUserOwnShoppingListIds
            )
        );

        $companyUserSharedShoppingListIds = $this->shoppingListRepository->getCompanyUserSharedShoppingListIdsByPermissionGroupName(
            $companyUserTransfer->getIdCompanyUser(),
            ShoppingListConfig::PERMISSION_GROUP_FULL_ACCESS
        );
        $companyBusinessUnitSharedShoppingListIds = $this->shoppingListRepository->getCompanyBusinessUnitSharedShoppingListIdsByPermissionGroupName(
            $companyUserTransfer->getFkCompanyBusinessUnit(),
            ShoppingListConfig::PERMISSION_GROUP_FULL_ACCESS
        );
        $companyBusinessUnitBlacklistedShoppingListIds = $this->shoppingListRepository->getBlacklistedShoppingListIdsByIdCompanyUser($companyUserTransfer->getIdCompanyUser());
        $companyBusinessUnitSharedShoppingListIds = array_diff($companyBusinessUnitSharedShoppingListIds, $companyBusinessUnitBlacklistedShoppingListIds);

        $companyUserPermissionCollectionTransfer = $this->addWritePermissionToPermissionCollectionTransfer(
            $companyUserPermissionCollectionTransfer,
            array_merge(
                $companyUserSharedShoppingListIds,
                $companyBusinessUnitSharedShoppingListIds,
                $companyUserOwnShoppingListIds
            )
        );

        return $companyUserPermissionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     * @param array $shoppingListIds
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function addReadPermissionToPermissionCollectionTransfer(
        PermissionCollectionTransfer $permissionCollectionTransfer,
        array $shoppingListIds
    ): PermissionCollectionTransfer {
        $permissionTransfer = (new PermissionTransfer())
            ->setKey(ShoppingListConfig::READ_SHOPPING_LIST_PERMISSION_PLUGIN_KEY)
            ->setConfiguration([
                ShoppingListConfig::PERMISSION_CONFIG_ID_SHOPPING_LIST_COLLECTION => $shoppingListIds,
            ]);

        $permissionCollectionTransfer = $permissionCollectionTransfer->addPermission($permissionTransfer);

        return $permissionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PermissionCollectionTransfer $permissionCollectionTransfer
     * @param array $shoppingListIds
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected function addWritePermissionToPermissionCollectionTransfer(
        PermissionCollectionTransfer $permissionCollectionTransfer,
        array $shoppingListIds
    ): PermissionCollectionTransfer {
        $permissionTransfer = (new PermissionTransfer())
            ->setKey(ShoppingListConfig::WRITE_SHOPPING_LIST_PERMISSION_PLUGIN_KEY)
            ->setConfiguration([
                ShoppingListConfig::PERMISSION_CONFIG_ID_SHOPPING_LIST_COLLECTION => $shoppingListIds,
            ]);

        $permissionCollectionTransfer = $permissionCollectionTransfer->addPermission($permissionTransfer);

        return $permissionCollectionTransfer;
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function getCustomerShoppingListCollectionByReference(string $customerReference): ShoppingListCollectionTransfer
    {
        return $this->shoppingListRepository->findCustomerShoppingLists($customerReference);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function expandProducts(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        $shoppingListItemsSkus = $this->getShoppingListItemsSkus($shoppingListItemCollectionTransfer);

        if (empty($shoppingListItemsSkus)) {
            return $shoppingListItemCollectionTransfer;
        }

        $expandedShoppingListItemCollection = new ShoppingListItemCollectionTransfer();
        $productConcreteTransfers = $this->productFacade->findProductConcretesBySkus($shoppingListItemsSkus);
        $keyedProductConcreteTransfers = $this->getKeyedProductConcreteTransfers($productConcreteTransfers);
        $shoppingListItems = $this->mapProductConcreteIdToShoppingListItem($shoppingListItemCollectionTransfer->getItems(), $keyedProductConcreteTransfers);

        foreach ($shoppingListItems as $shoppingListItem) {
            $expandedShoppingListItemCollection->addItem(
                $this->pluginExecutor->executeItemExpanderPlugins($shoppingListItem)
            );
        }

        return $expandedShoppingListItemCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return string[]
     */
    protected function getShoppingListItemsSkus(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): array
    {
        $shoppingListItemTransfers = (array)$shoppingListItemCollectionTransfer->getItems();

        return array_map(function (ShoppingListItemTransfer $shoppingListItemTransfer) {
            return $shoppingListItemTransfer[ShoppingListItemTransfer::SKU];
        }, $shoppingListItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getKeyedProductConcreteTransfers(array $productConcreteTransfers): array
    {
        $keyedProductConcreteTransfers = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $keyedProductConcreteTransfers[$productConcreteTransfer->getSku()] = $productConcreteTransfer;
        }

        return $keyedProductConcreteTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShoppingListItemTransfer[] $shoppingListItemTransfers
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $keyedProductConcreteTransfers
     *
     * @return \ArrayObject
     */
    protected function mapProductConcreteIdToShoppingListItem(ArrayObject $shoppingListItemTransfers, array $keyedProductConcreteTransfers): ArrayObject
    {
        foreach ($shoppingListItemTransfers as $shoppingListItemTransfer) {
            if (!isset($keyedProductConcreteTransfers[$shoppingListItemTransfer->getSku()])) {
                continue;
            }

            $productConcreteTransfer = $keyedProductConcreteTransfers[$shoppingListItemTransfer->getSku()];
            $shoppingListItemTransfer->setIdProduct($productConcreteTransfer->getIdProductConcrete());
            $shoppingListItemTransfer->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());
        }

        return $shoppingListItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer ...$shoppingListTransferCollections
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function mergeShoppingListCollections(ShoppingListCollectionTransfer ...$shoppingListTransferCollections): ShoppingListCollectionTransfer
    {
        $mergedShoppingListCollection = new ShoppingListCollectionTransfer();
        $mergedShoppingListIds = [];
        foreach ($shoppingListTransferCollections as $shoppingListCollection) {
            foreach ($shoppingListCollection->getShoppingLists() as $shoppingList) {
                if (!isset($mergedShoppingListIds[$shoppingList->getIdShoppingList()])) {
                    $mergedShoppingListCollection->addShoppingList($shoppingList);
                    $mergedShoppingListIds[$shoppingList->getIdShoppingList()] = true;
                }
            }
        }

        return $mergedShoppingListCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return bool
     */
    protected function checkReadPermission(ShoppingListTransfer $shoppingListTransfer): bool
    {
        if (!$shoppingListTransfer->getIdCompanyUser()) {
            return false;
        }

        return $this->can(
            'ReadShoppingListPermissionPlugin',
            $shoppingListTransfer->getIdCompanyUser(),
            $shoppingListTransfer->getIdShoppingList()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array
     */
    protected function findCompanyUserShoppingListIds(CompanyUserTransfer $companyUserTransfer): array
    {
        $companyUserOwnShoppingLists = $this->shoppingListRepository->findCustomerShoppingLists(
            $companyUserTransfer->getCustomer()->getCustomerReference()
        );
        $companyUserOwnShoppingListIds = [];

        foreach ($companyUserOwnShoppingLists->getShoppingLists() as $shoppingList) {
            $companyUserOwnShoppingListIds[] = $shoppingList->getIdShoppingList();
        }

        return $companyUserOwnShoppingListIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $shoppingListCollectionTransfer
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function filterBlacklistedShoppingLists(ShoppingListCollectionTransfer $shoppingListCollectionTransfer, int $idCompanyUser): ShoppingListCollectionTransfer
    {
        $filteredShoppingListCollectionTransfer = new ShoppingListCollectionTransfer();
        $blacklistedShoppingListsIds = $this->shoppingListRepository->getBlacklistedShoppingListIdsByIdCompanyUser($idCompanyUser);
        foreach ($shoppingListCollectionTransfer->getShoppingLists() as $shoppingListTransfer) {
            if (!in_array($shoppingListTransfer->getIdShoppingList(), $blacklistedShoppingListsIds, true)) {
                $filteredShoppingListCollectionTransfer->addShoppingList($shoppingListTransfer);
            }
        }

        return $filteredShoppingListCollectionTransfer;
    }
}
