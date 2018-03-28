<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer;
use Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer;
use Generated\Shared\Transfer\ShoppingListPaginationTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;
use Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface;

class Reader implements ReaderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface
     */
    protected $shoppingListRepository;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Plugin\ItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins;

    /**
     * @var \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface $shoppingListRepository
     * @param \Spryker\Zed\ShoppingList\Dependency\Plugin\ItemExpanderPluginInterface[] $itemExpanderPlugins
     */
    public function __construct(ShoppingListRepositoryInterface $shoppingListRepository, ShoppingListToProductFacadeInterface $productFacade, array $itemExpanderPlugins)
    {
        $this->shoppingListRepository = $shoppingListRepository;
        $this->itemExpanderPlugins = $itemExpanderPlugins;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListTransfer
    {
        return $this->shoppingListRepository->findCustomerShoppingListByName($shoppingListTransfer);
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
        $shoppingListOverviewRequestTransfer->getShoppingList()->requireCustomerReference();
        $shoppingListOverviewRequestTransfer->getShoppingList()->requireName();

        $shoppingListPaginationTransfer = $this->buildShoppingListPaginationTransfer($shoppingListOverviewRequestTransfer);

        $shoppingListOverviewResponseTransfer = $this->buildShoppingListOverviewResponseTransfer(
            $shoppingListOverviewRequestTransfer->getShoppingList(),
            $shoppingListPaginationTransfer
        );

        $shoppingListTransfer = $this->getShoppingList($shoppingListOverviewRequestTransfer->getShoppingList());

        if (!$shoppingListTransfer) {
            return $shoppingListOverviewResponseTransfer;
        }

        $shoppingListOverviewRequestTransfer->setShoppingList($shoppingListTransfer);
        $shoppingListOverviewResponseTransfer = $this->shoppingListRepository->findShoppingListPaginatedItems($shoppingListOverviewRequestTransfer);
        $shoppingListOverviewResponseTransfer = $this->expandProducts($shoppingListOverviewResponseTransfer);
        $shoppingListOverviewResponseTransfer->setShoppingList($shoppingListTransfer);
        $shoppingListOverviewResponseTransfer->setShoppingLists($this->getCustomerShoppingListCollectionByReference($shoppingListTransfer->getCustomerReference()));

        return $shoppingListOverviewResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPaginationTransfer
     */
    protected function buildShoppingListPaginationTransfer(ShoppingListOverviewRequestTransfer $shoppingListOverviewRequestTransfer): ShoppingListPaginationTransfer
    {
        return (new ShoppingListPaginationTransfer())
            ->setPage($shoppingListOverviewRequestTransfer->getPage())
            ->setItemsPerPage($shoppingListOverviewRequestTransfer->getItemsPerPage());
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingList
     * @param \Generated\Shared\Transfer\ShoppingListPaginationTransfer $shoppingListPaginationTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    protected function buildShoppingListOverviewResponseTransfer(ShoppingListTransfer $shoppingList, ShoppingListPaginationTransfer $shoppingListPaginationTransfer): ShoppingListOverviewResponseTransfer
    {
        return (new ShoppingListOverviewResponseTransfer())
            ->setShoppingList($shoppingList)
            ->setPagination($shoppingListPaginationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(CustomerTransfer $customerTransfer): ShoppingListCollectionTransfer
    {
        $customerReference = $customerTransfer
            ->requireIdCustomer()
            ->getCustomerReference();

        return $this->getCustomerShoppingListCollectionByReference($customerReference);
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
     * @param \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer $shoppingListOverviewResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListOverviewResponseTransfer
     */
    protected function expandProducts(ShoppingListOverviewResponseTransfer $shoppingListOverviewResponseTransfer): ShoppingListOverviewResponseTransfer
    {
        foreach ($shoppingListOverviewResponseTransfer->getItemsCollection()->getItems() as $item) {
            $idProduct = $this->productFacade->findProductConcreteIdBySku($item->getSku());
            $item->setIdProduct($idProduct);

            foreach ($this->itemExpanderPlugins as $itemExpanderPlugin) {
                $item = $itemExpanderPlugin->expandItem($item);
            }
        }

        return $shoppingListOverviewResponseTransfer;
    }
}
