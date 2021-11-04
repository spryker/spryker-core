<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList;

use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper\ShoppingListMapperInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;

class ShoppingListCreator implements ShoppingListCreatorInterface
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
    public function createShoppingList(ShoppingListTransfer $shoppingListTransfer): ShoppingListResponseTransfer
    {
        $shoppingListResponseTransfer = $this->shoppingListFacade->createShoppingList($shoppingListTransfer);

        if ($shoppingListResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListMapper->mapShoppingListResponseErrorsToRestCodes(
                $shoppingListResponseTransfer,
            );
        }

        $shoppingListTransfer->setUuid($shoppingListResponseTransfer->getShoppingList()->getUuid());

        return $this->shoppingListFacade->findShoppingListByUuid($shoppingListTransfer);
    }
}
