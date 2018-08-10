<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Business\Model;

use Generated\Shared\Transfer\ProductOptionCollectionTransfer;
use Spryker\Zed\ShoppingListProductOption\Dependency\Facade\ShoppingListProductOptionToProductOptionFacadeInterface;
use Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionRepositoryInterface;

class ShoppingListProductOptionReader implements ShoppingListProductOptionReaderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListProductOption\Dependency\Facade\ShoppingListProductOptionToProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @var \Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionRepositoryInterface
     */
    protected $shoppingListProductOptionRepository;

    /**
     * @param \Spryker\Zed\ShoppingListProductOption\Dependency\Facade\ShoppingListProductOptionToProductOptionFacadeInterface $productOptionFacade
     * @param \Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionRepositoryInterface $shoppingListProductOptionRepository
     */
    public function __construct(
        ShoppingListProductOptionToProductOptionFacadeInterface $productOptionFacade,
        ShoppingListProductOptionRepositoryInterface $shoppingListProductOptionRepository
    ) {
        $this->productOptionFacade = $productOptionFacade;
        $this->shoppingListProductOptionRepository = $shoppingListProductOptionRepository;
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ProductOptionCollectionTransfer
     */
    public function getShoppingListItemProductOptionsByIdShoppingListItem(int $idShoppingListItem): ProductOptionCollectionTransfer
    {
        $shoppingListItemProductOptionIds = $this->shoppingListProductOptionRepository
            ->findShoppingListItemProductOptionIdsByFkShoppingListItem($idShoppingListItem);

        //todo:implement facade method and return real collection ProductOptionCollectionTransfer
        return new ProductOptionCollectionTransfer();
    }
}
