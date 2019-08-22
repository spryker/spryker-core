<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption;

use Generated\Shared\Transfer\ProductOptionCollectionTransfer;
use Generated\Shared\Transfer\ProductOptionCriteriaTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface;
use Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorRepositoryInterface;

class ShoppingListProductOptionReader implements ShoppingListProductOptionReaderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @var \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorRepositoryInterface
     */
    protected $shoppingListProductOptionRepository;

    /**
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface $productOptionFacade
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorRepositoryInterface $shoppingListProductOptionRepository
     */
    public function __construct(
        ShoppingListProductOptionConnectorToProductOptionFacadeInterface $productOptionFacade,
        ShoppingListProductOptionConnectorRepositoryInterface $shoppingListProductOptionRepository
    ) {
        $this->productOptionFacade = $productOptionFacade;
        $this->shoppingListProductOptionRepository = $shoppingListProductOptionRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionCollectionTransfer
     */
    public function getShoppingListItemProductOptionsByIdShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ProductOptionCollectionTransfer
    {
        $productOptionCriteriaTransfer = $this->getProductOptionCriteriaTransfer($shoppingListItemTransfer);

        return $this->productOptionFacade->getProductOptionCollectionByProductOptionCriteria($productOptionCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionCriteriaTransfer
     */
    protected function getProductOptionCriteriaTransfer(ShoppingListItemTransfer $shoppingListItemTransfer): ProductOptionCriteriaTransfer
    {
        $productOptionCriteriaTransfer = new ProductOptionCriteriaTransfer();

        $shoppingListItemProductOptionIds = $this->shoppingListProductOptionRepository
            ->getShoppingListItemProductOptionIdsByIdShoppingListItem(
                $shoppingListItemTransfer->getIdShoppingListItem()
            );

        $productOptionCriteriaTransfer->setProductOptionIds($shoppingListItemProductOptionIds)
            ->setProductConcreteSku($shoppingListItemTransfer->getSku())
            ->setProductOptionGroupIsActive(true);

        return $productOptionCriteriaTransfer;
    }
}
