<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList;

use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig;
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
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper\ShoppingListMapperInterface $shoppingListMapper
     */
    public function __construct(
        ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade,
        ShoppingListMapperInterface $shoppingListMapper
    ) {
        $this->shoppingListFacade = $shoppingListFacade;
        $this->shoppingListMapper = $shoppingListMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function updateShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransferByUuid = $this->shoppingListFacade->findShoppingListByUuid($shoppingListTransfer);
        if ($shoppingListResponseTransferByUuid->getIsSuccess() === false) {
            return $shoppingListResponseTransferByUuid->setErrors([ShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND]);
        }
        $shoppingListTransfer = $this->shoppingListMapper->mapShoppingListResponseTransferToShoppingListTransfer(
            $shoppingListResponseTransferByUuid,
            $shoppingListTransfer
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
