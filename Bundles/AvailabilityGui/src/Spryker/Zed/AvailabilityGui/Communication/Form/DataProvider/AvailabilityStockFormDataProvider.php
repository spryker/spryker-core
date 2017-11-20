<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AvailabilityStockTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface;

class AvailabilityStockFormDataProvider
{
    const DATA_CLASS = 'data_class';

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface
     */
    protected $stockFacade;

    /**
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface $stockFacade
     */
    public function __construct(AvailabilityGuiToStockInterface $stockFacade)
    {
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param int $idProduct
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\AvailabilityStockTransfer
     */
    public function getData($idProduct, $sku)
    {
        $stockProducts = $this->stockFacade->getStockProductsByIdProduct($idProduct);
        $stockProducts = $this->sortProducts($stockProducts);

        $availabilityGuiStockTransfer = $this->loadAvailabilityGuiStockTransfer($sku, $stockProducts);
        $this->addEmptyStockType($availabilityGuiStockTransfer);

        return $availabilityGuiStockTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            static::DATA_CLASS => AvailabilityStockTransfer::class,
        ];
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\StockProductTransfer[] $stockProducts
     *
     * @return \Generated\Shared\Transfer\AvailabilityStockTransfer
     */
    protected function loadAvailabilityGuiStockTransfer($sku, array $stockProducts)
    {
        $availabilityGuiStockTransfer = new AvailabilityStockTransfer();
        $availabilityGuiStockTransfer->setSku($sku);

        foreach ($stockProducts as $stockProductTransfer) {
            $availabilityGuiStockTransfer->addStockProduct($stockProductTransfer);
        }

        return $availabilityGuiStockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityStockTransfer $availabilityStockTransfer
     *
     * @return void
     */
    protected function addEmptyStockType($availabilityStockTransfer)
    {
        $allStockType = $this->stockFacade->getAvailableStockTypes();

        foreach ($allStockType as $type) {
            if ($this->stockTypeExist($availabilityStockTransfer, $type)) {
                continue;
            }
            $stockProductTransfer = new StockProductTransfer();
            $stockProductTransfer->setStockType($type);
            $stockProductTransfer->setQuantity(0);

            $availabilityStockTransfer->addStockProduct($stockProductTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityStockTransfer $availabilityStockTransfer
     * @param \Orm\Zed\Stock\Persistence\SpyStock $type
     *
     * @return bool
     */
    protected function stockTypeExist($availabilityStockTransfer, $type)
    {
        foreach ($availabilityStockTransfer->getStocks() as $stockProduct) {
            if ($stockProduct->getStockType() === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $stockProducts
     *
     * @return array
     */
    protected function sortProducts(array $stockProducts)
    {
        usort(
            $stockProducts,
            function (StockProductTransfer $stockProductLeftTransfer, StockProductTransfer $stockProductRightTransfer) {
                return strcmp($stockProductLeftTransfer->getStockType(), $stockProductRightTransfer->getStockType());
            }
        );

        return $stockProducts;
    }
}
