<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Checker;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToStoreFacadeInterface;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface;

class CartItemSalesUnitChecker implements CartItemSalesUnitCheckerInterface
{
    protected const GLOSSARY_KEY_CART_ITEM_SALES_UNIT_IS_NOT_FOUND = 'cart.item.sales_unit.not_found';

    protected const MESSAGE_TYPE_ERROR = 'error';

    protected const ID_TRANSLATION_PARAMETER = '%id%';
    protected const SKU_TRANSLATION_PARAMETER = '%sku%';

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface
     */
    protected $productMeasurementUnitRepository;

    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository
     * @param \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductMeasurementUnitRepositoryInterface $productMeasurementUnitRepository,
        ProductMeasurementUnitToStoreFacadeInterface $storeFacade
    ) {
        $this->productMeasurementUnitRepository = $productMeasurementUnitRepository;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartItemSalesUnit(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())->setIsSuccess(true);
        $productConcreteSkus = $this->getProductConcreteSkus($cartChangeTransfer);
        if (!$productConcreteSkus) {
            return $cartPreCheckResponseTransfer;
        }

        $storeTransfer = $this->storeFacade->getCurrentStore();
        $indexedProductMeasurementSalesUnitIds = $this->productMeasurementUnitRepository
            ->findIndexedStoreAwareDefaultProductMeasurementSalesUnitIds(
                $productConcreteSkus,
                $storeTransfer->getIdStore()
            );

        if (!$indexedProductMeasurementSalesUnitIds) {
            return $cartPreCheckResponseTransfer;
        }

        foreach ($cartChangeTransfer->getItems() as $index => $itemTransfer) {
            $cartPreCheckResponseTransfer = $this->checkSalesUnit(
                $itemTransfer,
                $cartPreCheckResponseTransfer,
                $indexedProductMeasurementSalesUnitIds[$itemTransfer->getSku()]
            );
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     * @param int $indexedIdProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function checkSalesUnit(
        ItemTransfer $itemTransfer,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer,
        int $indexedIdProductMeasurementSalesUnit
    ): CartPreCheckResponseTransfer {
        $productMeasurementSalesUnitTransfer = $itemTransfer->getAmountSalesUnit();
        if (!$productMeasurementSalesUnitTransfer) {
            return $cartPreCheckResponseTransfer;
        }

        $idProductMeasurementSalesUnit = $productMeasurementSalesUnitTransfer->getIdProductMeasurementSalesUnit();
        if ($idProductMeasurementSalesUnit === $indexedIdProductMeasurementSalesUnit) {
            return $cartPreCheckResponseTransfer;
        }

        $messageParameters = [
            static::ID_TRANSLATION_PARAMETER => $idProductMeasurementSalesUnit,
            static::SKU_TRANSLATION_PARAMETER => $itemTransfer->getSku(),
        ];

        return $cartPreCheckResponseTransfer
            ->addMessage($this->createMessageTransfer($messageParameters))
            ->setIsSuccess(false);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return string[]
     */
    protected function getProductConcreteSkus(CartChangeTransfer $cartChangeTransfer): array
    {
        $productConcreteSkus = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getQuantitySalesUnit()) {
                $productConcreteSkus[] = $itemTransfer->getSku();
            }
        }

        return $productConcreteSkus;
    }

    /**
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(array $parameters): MessageTransfer
    {
        return (new MessageTransfer())
            ->setType(static::MESSAGE_TYPE_ERROR)
            ->setValue(static::GLOSSARY_KEY_CART_ITEM_SALES_UNIT_IS_NOT_FOUND)
            ->setParameters($parameters);
    }
}
