<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem;

use Generated\Shared\Transfer\ShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListReaderInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\Mapper\ShoppingListItemMapperInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;

class ShoppingListItemAdder implements ShoppingListItemAdderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface
     */
    protected $shoppingListFacade;

    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\Mapper\ShoppingListItemMapperInterface
     */
    protected $shoppingListItemMapper;

    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListReaderInterface
     */
    protected $shoppingListReader;

    /**
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\Mapper\ShoppingListItemMapperInterface $shoppingListItemMapper
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListReaderInterface $shoppingListReader
     */
    public function __construct(
        ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade,
        ShoppingListItemMapperInterface $shoppingListItemMapper,
        ShoppingListReaderInterface $shoppingListReader
    ) {
        $this->shoppingListFacade = $shoppingListFacade;
        $this->shoppingListItemMapper = $shoppingListItemMapper;
        $this->shoppingListReader = $shoppingListReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function addShoppingListItem(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListItemRequestTransfer->requireShoppingListItem();
        $shoppingListResponseTransferByUuid = $this->shoppingListFacade->findShoppingListByUuid(
            $this->shoppingListItemMapper->mapShoppingListItemRequestTransferToShoppingListTransfer(
                $shoppingListItemRequestTransfer,
                new ShoppingListTransfer(),
            ),
        );

        if (!$shoppingListResponseTransferByUuid->getIsSuccess()) {
            return $this->shoppingListItemMapper->mapShoppingListResponseErrorsToShoppingListItemResponseErrors(
                $shoppingListResponseTransferByUuid,
                new ShoppingListItemResponseTransfer(),
            );
        }

        $shoppingListItem = $shoppingListItemRequestTransfer->getShoppingListItem()
            ->setIdCompanyUser($shoppingListResponseTransferByUuid->getShoppingList()->getIdCompanyUser())
            ->setFkShoppingList($shoppingListResponseTransferByUuid->getShoppingList()->getIdShoppingList());

        $shoppingListItemResponseTransfer = $this->shoppingListFacade->addShoppingListItem($shoppingListItem);

        if ($shoppingListItemResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListItemMapper->mapShoppingListResponseErrorsToRestCodes(
                $shoppingListItemResponseTransfer,
            );
        }

        return $shoppingListItemResponseTransfer;
    }
}
