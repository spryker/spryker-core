<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList;

use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper\ShoppingListMapperInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;

class ShoppingListUpdater implements ShoppingListUpdaterInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface
     */
    protected $shoppingListFacade;

    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper\ShoppingListMapperInterface
     */
    protected $shoppingListMapper;

    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListReaderInterface
     */
    protected $shoppingListReader;

    /**
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper\ShoppingListMapperInterface $shoppingListMapper
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListReaderInterface $shoppingListReader
     */
    public function __construct(
        ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade,
        ShoppingListMapperInterface $shoppingListMapper,
        ShoppingListReaderInterface $shoppingListReader
    ) {
        $this->shoppingListFacade = $shoppingListFacade;
        $this->shoppingListMapper = $shoppingListMapper;
        $this->shoppingListReader = $shoppingListReader;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListRequestTransfer $restShoppingListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function updateShoppingList(
        RestShoppingListRequestTransfer $restShoppingListRequestTransfer
    ): ShoppingListResponseTransfer {
        $restShoppingListRequestTransfer
            ->requireShoppingList()
            ->requireCustomerReference()
            ->requireCompanyUserUuid();
        $restShoppingListRequestTransfer->getShoppingList()
            ->requireName()
            ->requireUuid();

        $shoppingListResponseTransferByUuid = $this->shoppingListReader->findShoppingListByUuid($restShoppingListRequestTransfer);

        if ($shoppingListResponseTransferByUuid->getIsSuccess() === false) {
            return $shoppingListResponseTransferByUuid;
        }

        $shoppingListTransfer = $this->shoppingListMapper->mapShoppingListResponseTransferToShoppingListTransfer(
            $shoppingListResponseTransferByUuid,
            (new ShoppingListTransfer())
                ->setName($restShoppingListRequestTransfer->getShoppingList()->getName())
        );

        $shoppingListResponseTransfer = $this->shoppingListFacade->updateShoppingList($shoppingListTransfer);

        if ($shoppingListResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListMapper->mapShoppingListResponseErrorsToRestCodes(
                $shoppingListResponseTransfer
            );
        }

        return $this->shoppingListFacade->findShoppingListByUuid($shoppingListTransfer);
    }
}
