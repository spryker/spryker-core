<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPermissionGroupCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Shared\ShoppingList\ShoppingListConfig;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class ShoppingListReader implements ShoppingListReaderInterface
{
    use PermissionAwareTrait;

    protected const MESSAGE_SHOPPING_LIST_REMOVED = 'shopping_list.already_removed';

    protected const MESSAGE_SHOPPING_LIST_NO_ACCESS = 'shopping_list.no_access';

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
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\ShoppingList\ShoppingListConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface $customerFacade
     * @param \Spryker\Zed\ShoppingList\Business\ShoppingListItem\ShoppingListItemPluginExecutorInterface $pluginExecutor
     * @param \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\ShoppingList\ShoppingListConfig $config
     */
    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
        ShoppingListToProductFacadeInterface $productFacade,
        ShoppingListToCompanyUserFacadeInterface $customerFacade,
        ShoppingListItemPluginExecutorInterface $pluginExecutor,
        ShoppingListToMessengerFacadeInterface $messengerFacade,
        \Spryker\Zed\ShoppingList\ShoppingListConfig $config
    ) {
        $this->shoppingListRepository = $shoppingListRepository;
        $this->productFacade = $productFacade;
        $this->companyUserFacade = $customerFacade;
        $this->pluginExecutor = $pluginExecutor;
        $this->messengerFacade = $messengerFacade;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer|null $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function getShoppingListOverviewErrorMessageTransfer(?ShoppingListTransfer $shoppingListTransfer): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        if ($shoppingListTransfer === null) {
            $messageTransfer->setValue(static::MESSAGE_SHOPPING_LIST_REMOVED);

            return $messageTransfer;
        }

        $messageTransfer->setValue(static::MESSAGE_SHOPPING_LIST_NO_ACCESS);

        return $messageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListTransfer = $this->getShoppingListTransferWithCompanyData($shoppingListTransfer);

        if (!$shoppingListTransfer->getIdShoppingList()) {
            return $shoppingListTransfer;
        }

        $shoppingListItemCollectionTransfer = (new ShoppingListItemCollectionTransfer())
            ->setItems($shoppingListTransfer->getItems());

        $shoppingListItemCollectionTransfer = $this->expandShoppingListItemCollectionTransfer($shoppingListItemCollectionTransfer);
        $shoppingListTransfer->setItems($shoppingListItemCollectionTransfer->getItems());

        return $shoppingListTransfer;
    }

    /**
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

        $shoppingListTransfer = $this
            ->getShoppingListTransferWithCompanyData($shoppingListOverviewRequestTransfer->getShoppingList());

        if (!$shoppingListTransfer->getIdShoppingList()) {
            $shoppingListOverviewResponseTransfer->setIsSuccess(false);

            return $shoppingListOverviewResponseTransfer;
        }

        $shoppingListOverviewRequestTransfer->setShoppingList($shoppingListTransfer);
        $shoppingListOverviewResponseTransfer = $this->shoppingListRepository
            ->findShoppingListPaginatedItems($shoppingListOverviewRequestTransfer);

        $shoppingListItemCollection = $this->expandShoppingListItemCollectionTransfer(
            $shoppingListOverviewResponseTransfer->getItemsCollection(),
            $shoppingListOverviewRequestTransfer->getCurrencyIsoCode(),
            $shoppingListOverviewRequestTransfer->getPriceMode()
        );
        $shoppingListOverviewResponseTransfer->setItemsCollection($shoppingListItemCollection);

        $customerTransfer = new CustomerTransfer();
        $requestCompanyUserTransfer = $this->companyUserFacade
            ->getCompanyUserById($shoppingListOverviewRequestTransfer->getShoppingList()->getIdCompanyUser());

        $customerTransfer->setCustomerReference($requestCompanyUserTransfer->getCustomer()->getCustomerReference());
        $customerTransfer->setCompanyUserTransfer($requestCompanyUserTransfer);

        $shoppingListOverviewResponseTransfer->setShoppingList($shoppingListTransfer);

        if ($this->config->isShoppingListOverviewWithShoppingLists()) {
            $shoppingListOverviewResponseTransfer->setShoppingLists($this->getCustomerShoppingListCollection($customerTransfer));
        }
        $shoppingListOverviewResponseTransfer->setIsSuccess(true);

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

        return $this->expandShoppingListItemCollectionTransfer($shoppingListItemCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function getShoppingListItemCollectionTransfer(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        $shoppingListItemIds = [];

        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemIds[] = $shoppingListItemTransfer->getIdShoppingListItem();
        }

        $shoppingListItemCollectionTransfer = $this->shoppingListRepository->findShoppingListItemsByIds($shoppingListItemIds);

        return $this->expandShoppingListItemCollectionTransfer($shoppingListItemCollectionTransfer);
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
            array_unique(array_merge(
                $this->shoppingListRepository->findCompanyUserSharedShoppingListsIds($companyUserTransfer->getIdCompanyUser()),
                $companyBusinessUnitSharedShoppingListIds,
                $companyUserOwnShoppingListIds
            ))
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
            array_unique(array_merge(
                $companyUserSharedShoppingListIds,
                $companyBusinessUnitSharedShoppingListIds,
                $companyUserOwnShoppingListIds
            ))
        );

        return $companyUserPermissionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function findShoppingListByUuid(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListTransfer->requireUuid();

        $shoppingListResponseTransfer = (new ShoppingListResponseTransfer())
            ->setIsSuccess(false);

        $shoppingListTransferByRequest = (new ShoppingListTransfer())->fromArray($shoppingListTransfer->toArray());
        $shoppingListTransferByUuid = $this->shoppingListRepository->findShoppingListByUuid($shoppingListTransferByRequest);

        if ($shoppingListTransferByUuid === null) {
            return $shoppingListResponseTransfer;
        }

        $shoppingListTransferByRequest->setIdShoppingList($shoppingListTransferByUuid->getIdShoppingList());
        $shoppingListTransferById = $this->getShoppingList($shoppingListTransferByRequest);

        if ($shoppingListTransferById->getIdShoppingList() === null) {
            return $shoppingListResponseTransfer;
        }

        return $shoppingListResponseTransfer->setIsSuccess(true)
            ->setShoppingList($shoppingListTransferById);
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
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected function getShoppingListTransferWithCompanyData(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        $shoppingListTransfer = $this->shoppingListRepository->findShoppingListById($shoppingListTransfer);

        if ($shoppingListTransfer === null || !$this->checkReadPermission($shoppingListTransfer)) {
            $messageTransfer = $this->getShoppingListOverviewErrorMessageTransfer($shoppingListTransfer);
            $this->messengerFacade->addErrorMessage($messageTransfer);

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

        return $shoppingListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     * @param string|null $currencyIsoCode
     * @param string|null $priceMode
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function expandShoppingListItemCollectionTransfer(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer,
        ?string $currencyIsoCode = null,
        ?string $priceMode = null
    ): ShoppingListItemCollectionTransfer {
        $shoppingListItemsSkus = $this->getShoppingListItemsSkus($shoppingListItemCollectionTransfer);

        if (empty($shoppingListItemsSkus)) {
            return $shoppingListItemCollectionTransfer;
        }

        $productConcreteTransfers = $this->productFacade->findProductConcretesBySkus($shoppingListItemsSkus);
        $keyedProductConcreteTransfers = $this->getKeyedProductConcreteTransfers($productConcreteTransfers);
        $shoppingListItemTransfers = $this->mapProductConcreteIdToShoppingListItem(
            $shoppingListItemCollectionTransfer->getItems(),
            $keyedProductConcreteTransfers
        );
        $shoppingListItemCollectionTransfer->setItems($shoppingListItemTransfers);

        $shoppingListItemCollectionTransfer = $this->expandShoppingListItemCollectionTransferWithCurrencyParameters(
            $shoppingListItemCollectionTransfer,
            $currencyIsoCode,
            $priceMode
        );

        return $this->expandShoppingListItemCollectionTransferWithPlugins($shoppingListItemCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     * @param string|null $currencyIsoCode
     * @param string|null $priceMode
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function expandShoppingListItemCollectionTransferWithCurrencyParameters(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer,
        ?string $currencyIsoCode,
        ?string $priceMode
    ): ShoppingListItemCollectionTransfer {
        if ($currencyIsoCode === null && $priceMode === null) {
            return $shoppingListItemCollectionTransfer;
        }

        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $shoppingListItemTransfer
                ->setCurrencyIsoCode($currencyIsoCode)
                ->setPriceMode($priceMode);
        }

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function expandShoppingListItemCollectionTransferWithPlugins(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        $shoppingListItemCollectionTransfer = $this
            ->executeShoppingListItemExpanderPlugins($shoppingListItemCollectionTransfer);

        $shoppingListItemCollectionTransfer = $this->pluginExecutor
            ->executeShoppingListItemCollectionExpanderPlugins($shoppingListItemCollectionTransfer);

        return $shoppingListItemCollectionTransfer;
    }

    /**
     * @deprecated Added for BC reasons, will be removed in the next major release.
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    protected function executeShoppingListItemExpanderPlugins(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer {
        $expandedShoppingListItemTransfers = new ArrayObject();

        foreach ($shoppingListItemCollectionTransfer->getItems() as $shoppingListItemTransfer) {
            $expandedShoppingListItemTransfers->append(
                $this->pluginExecutor->executeItemExpanderPlugins($shoppingListItemTransfer)
            );
        }

        $shoppingListItemCollectionTransfer->setItems($expandedShoppingListItemTransfers);

        return $shoppingListItemCollectionTransfer;
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
     * @return \ArrayObject|\Generated\Shared\Transfer\ShoppingListItemTransfer[]
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
    protected function filterBlacklistedShoppingLists(
        ShoppingListCollectionTransfer $shoppingListCollectionTransfer,
        int $idCompanyUser
    ): ShoppingListCollectionTransfer {
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
