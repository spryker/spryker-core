<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem;

use ArrayObject;
use Generated\Shared\Transfer\ShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemResponseTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\Mapper\ShoppingListItemMapperInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;

class ShoppingListItemReader implements ShoppingListItemReaderInterface
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
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\Mapper\ShoppingListItemMapperInterface $shoppingListItemMapper
     */
    public function __construct(
        ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade,
        ShoppingListItemMapperInterface $shoppingListItemMapper
    ) {
        $this->shoppingListFacade = $shoppingListFacade;
        $this->shoppingListItemMapper = $shoppingListItemMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemResponseTransfer
     */
    public function findShoppingListItem(
        ShoppingListItemRequestTransfer $shoppingListItemRequestTransfer
    ): ShoppingListItemResponseTransfer {
        $shoppingListResponseTransfer = $this->shoppingListFacade->findShoppingListByUuid(
            $this->shoppingListItemMapper->mapShoppingListItemRequestTransferToShoppingListTransfer(
                $shoppingListItemRequestTransfer,
                new ShoppingListTransfer()
            )
        );

        if ($shoppingListResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListItemMapper->mapShoppingListResponseErrorsToShoppingListItemResponseErrors(
                $shoppingListResponseTransfer,
                new ShoppingListItemResponseTransfer()
            );
        }

        $shoppingListItemTransfer = $this->findShoppingListItemTransfer(
            $shoppingListResponseTransfer->getShoppingList()->getItems(),
            $shoppingListItemRequestTransfer->getShoppingListItem()->getUuid()
        );

        if (!$shoppingListItemTransfer) {
            return (new ShoppingListItemResponseTransfer())->setIsSuccess(false)
                ->addError(ShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_ITEM_NOT_FOUND);
        }

        return (new ShoppingListItemResponseTransfer())->setIsSuccess(true)
            ->setShoppingListItem(
                $shoppingListItemTransfer
                    ->setIdCompanyUser($shoppingListResponseTransfer->getShoppingList()->getIdCompanyUser())
                    ->setFkShoppingList($shoppingListResponseTransfer->getShoppingList()->getIdShoppingList())
            );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ShoppingListItemTransfer[] $shoppingListItemTransfers
     * @param string $uuidShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer|null
     */
    protected function findShoppingListItemTransfer(
        ArrayObject $shoppingListItemTransfers,
        string $uuidShoppingListItem
    ): ?ShoppingListItemTransfer {
        foreach ($shoppingListItemTransfers as $shoppingListItemTransfer) {
            if ($shoppingListItemTransfer->getUuid() === $uuidShoppingListItem) {
                return (new ShoppingListItemTransfer())->fromArray($shoppingListItemTransfer->modifiedToArray());
            }
        }

        return null;
    }
}
