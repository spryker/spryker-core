<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShoppingListDataImport\Business\DataImportStep;

use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use LogicException;
use Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferShoppingListDataImport\Business\Exception\ProductOfferNotFoundException;
use Spryker\Zed\ProductOfferShoppingListDataImport\Business\Model\DataSet\ShoppingListItemDataSetInterface;
use Spryker\Zed\ProductOfferShoppingListDataImport\Communication\Dependency\Facade\ProductOfferShoppingListDataImportToProductOfferFacadeInterface;

class ProductOfferShoppingListItemDataImportWriterStep implements DataImportStepInterface
{
 /**
  * @var \Spryker\Zed\ProductOfferShoppingListDataImport\Communication\Dependency\Facade\ProductOfferShoppingListDataImportToProductOfferFacadeInterface
  */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferShoppingListDataImport\Communication\Dependency\Facade\ProductOfferShoppingListDataImportToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(ProductOfferShoppingListDataImportToProductOfferFacadeInterface $productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $shoppingListItemEntity = $this->createShoppingListItemQuery()
            ->filterByKey($dataSet[ShoppingListItemDataSetInterface::COLUMN_SHOPPING_LIST_ITEM_KEY])
            ->findOne();

        if (!$shoppingListItemEntity) {
            throw new EntityNotFoundException(
                sprintf("ShoppingListItem entity with key '%s' not found", $dataSet[ShoppingListItemDataSetInterface::COLUMN_SHOPPING_LIST_ITEM_KEY]),
            );
        }

        $shoppingListItemEntity->setProductOfferReference($dataSet[ShoppingListItemDataSetInterface::COLUMN_PRODUCT_OFFER_REFERENCE])
            ->save();
    }

    /**
     * @return \Orm\Zed\ShoppingList\Persistence\SpyShoppingListItemQuery
     */
    protected function createShoppingListItemQuery(): SpyShoppingListItemQuery
    {
        return SpyShoppingListItemQuery::create();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\ProductOfferShoppingListDataImport\Business\Exception\ProductOfferNotFoundException
     * @throws \LogicException
     *
     * @return void
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        $productOfferReference = $dataSet[ShoppingListItemDataSetInterface::COLUMN_PRODUCT_OFFER_REFERENCE] ?? null;

        if (!$productOfferReference) {
            throw new LogicException(
                sprintf('Product offer reference cannot be null or empty'),
            );
        }

        $productOffer = $this->productOfferFacade->findOne(
            (new ProductOfferCriteriaTransfer())->setProductOfferReference($productOfferReference),
        );

        if (!$productOffer) {
            throw new ProductOfferNotFoundException(
                sprintf('Product offer with product offer reference reference: %s not found', $productOfferReference),
            );
        }
    }
}
