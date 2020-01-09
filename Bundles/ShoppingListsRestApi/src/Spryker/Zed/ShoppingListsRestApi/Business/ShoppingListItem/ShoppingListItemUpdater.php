<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem;

use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\Mapper\ShoppingListItemMapperInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;

class ShoppingListItemUpdater implements ShoppingListItemUpdaterInterface
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
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemReaderInterface
     */
    protected $shoppingListItemReader;

    /**
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\Mapper\ShoppingListItemMapperInterface $shoppingListItemMapper
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemReaderInterface $shoppingListItemReader
     */
    public function __construct(
        ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade,
        ShoppingListItemMapperInterface $shoppingListItemMapper,
        ShoppingListItemReaderInterface $shoppingListItemReader
    ) {
        $this->shoppingListFacade = $shoppingListFacade;
        $this->shoppingListItemMapper = $shoppingListItemMapper;
        $this->shoppingListItemReader = $shoppingListItemReader;
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function updateShoppingListItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListItemResponseTransfer = $this->shoppingListItemReader->findShoppingListItem(
            $restShoppingListItemRequestTransfer
        );

        if ($shoppingListItemResponseTransfer->getIsSuccess() === false) {
            return $shoppingListItemResponseTransfer;
        }

        $shoppingListItemTransfer = $shoppingListItemResponseTransfer->getShoppingListItem()
            ->fromArray(
                $restShoppingListItemRequestTransfer->getShoppingListItem()->modifiedToArray(),
                true
            );

        $shoppingListItemResponseTransfer = $this->shoppingListFacade->updateShoppingListItemById($shoppingListItemTransfer);

        if ($shoppingListItemResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListItemMapper->mapShoppingListResponseErrorsToRestCodes(
                $shoppingListItemResponseTransfer
            );
        }

        return $shoppingListItemResponseTransfer;
    }
}
