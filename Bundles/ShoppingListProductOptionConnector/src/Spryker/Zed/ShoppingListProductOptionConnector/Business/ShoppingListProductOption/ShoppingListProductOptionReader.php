<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption;

use Generated\Shared\Transfer\ProductOptionCollectionTransfer;
use Generated\Shared\Transfer\ProductOptionCriteriaTransfer;
use Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface;
use Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToShoppingListFacadeInterface;
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
     * @var \Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToShoppingListFacadeInterface
     */
    protected $shoppingListFacade;

    /**
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface $productOptionFacade
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorRepositoryInterface $shoppingListProductOptionRepository
     * @param \Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToShoppingListFacadeInterface $shoppingListFacade
     */
    public function __construct(
        ShoppingListProductOptionConnectorToProductOptionFacadeInterface $productOptionFacade,
        ShoppingListProductOptionConnectorRepositoryInterface $shoppingListProductOptionRepository,
        ShoppingListProductOptionConnectorToShoppingListFacadeInterface $shoppingListFacade
    ) {
        $this->productOptionFacade = $productOptionFacade;
        $this->shoppingListProductOptionRepository = $shoppingListProductOptionRepository;
        $this->shoppingListFacade = $shoppingListFacade;
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ProductOptionCollectionTransfer
     */
    public function getShoppingListItemProductOptionsByIdShoppingListItem(int $idShoppingListItem): ProductOptionCollectionTransfer
    {
        $productOptionCriteriaTransfer = $this->getProductOptionCriteriaTransfer($idShoppingListItem);

        return $this->productOptionFacade->getProductOptionCollectionByProductOptionCriteria($productOptionCriteriaTransfer);
    }

    /**
     * @param int $idShoppingListItem
     *
     * @return \Generated\Shared\Transfer\ProductOptionCriteriaTransfer
     */
    protected function getProductOptionCriteriaTransfer(int $idShoppingListItem): ProductOptionCriteriaTransfer
    {
        $shoppingListItemProductOptionIds = $this->shoppingListProductOptionRepository
            ->getShoppingListItemProductOptionIdsByIdShoppingListItem($idShoppingListItem);

        $productConcreteSku = $this->shoppingListFacade
            ->getShoppingListItemById($idShoppingListItem)
            ->requireSku()
            ->getSku();

        return (new ProductOptionCriteriaTransfer())
            ->setProductOptionIds($shoppingListItemProductOptionIds)
            ->setProductOptionGroupIsActive(true)
            ->setProductConcreteSku($productConcreteSku);
    }
}
