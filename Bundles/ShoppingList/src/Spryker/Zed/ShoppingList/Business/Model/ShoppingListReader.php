<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Shared\ShoppingList\ShoppingListConfig;
use Spryker\Zed\Kernel\PermissionAwareTrait;
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
     * @var \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface $customerFacade
     * @param \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ItemExpanderPluginInterface[] $itemExpanderPlugins
     */
    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListToProductFacadeInterface $productFacade,
        ShoppingListToCompanyUserFacadeInterface $customerFacade,
        array $itemExpanderPlugins
    ) {
        $this->shoppingListRepository = $shoppingListRepository;
        $this->itemExpanderPlugins = $itemExpanderPlugins;
        $this->productFacade = $productFacade;
        $this->companyUserFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListTransfer = $this->shoppingListRepository->findShoppingListById($shoppingListTransfer);

        if (!$this->checkReadPermission($shoppingListTransfer)) {
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
        $this->expandProducts($shoppingListItemCollectionTransfer);
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

        return $this->shoppingListRepository->findCustomerShoppingListsItemsByIds($shoppingListIds);
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

        return $this->shoppingListRepository->findShoppingListItemsByIds($shoppingListItemIds);
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListPermissionGroupTransfer
     */
    public function getShoppingListPermissionGroup(): ShoppingListPermissionGroupTransfer
    {
        return $this->shoppingListRepository->getShoppingListPermissionGroup();
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
     * TODO: switch from loop -> query to SKU IN query (create facade function + add to bridge)
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function expandProducts(ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer): ShoppingListItemCollectionTransfer
    {
        foreach ($shoppingListItemCollectionTransfer->getItems() as $item) {
            $idProduct = $this->productFacade->findProductConcreteIdBySku($item->getSku());
            $item->setIdProduct($idProduct);

            foreach ($this->itemExpanderPlugins as $itemExpanderPlugin) {
                $item = $itemExpanderPlugin->expandItem($item);
            }
        }

        return $shoppingListItemCollectionTransfer;
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
     * @param \Generated\Shared\Transfer\ShoppingListCollectionTransfer $businessUnitSharedShoppingLists
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function filterBlacklistedShoppingLists(ShoppingListCollectionTransfer $businessUnitSharedShoppingLists, int $idCompanyUser): ShoppingListCollectionTransfer
    {
        $blacklistedShoppingListsIds = $this->shoppingListRepository->getBlacklistedShoppingListIdsByIdCompanyUser($idCompanyUser);
        foreach ($businessUnitSharedShoppingLists->getShoppingLists() as $index => $shoppingListTransfer) {
            if (in_array($shoppingListTransfer->getIdShoppingList(), $blacklistedShoppingListsIds, true)) {
                $businessUnitSharedShoppingLists->getShoppingLists()->offsetUnset($index);
            }
        }

        return $businessUnitSharedShoppingLists;
    }
}
