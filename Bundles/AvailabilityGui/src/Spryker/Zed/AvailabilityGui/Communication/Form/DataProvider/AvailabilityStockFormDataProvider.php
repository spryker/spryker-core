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

        $AvailabilityGuiStockTransfer = $this->loadAvailabilityGuiStockTransfer($sku, $stockProducts);
        $this->addEmptyStockType($AvailabilityGuiStockTransfer);

        return $AvailabilityGuiStockTransfer;
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
     * @param array|\Generated\Shared\Transfer\StockProductTransfer[] $stockProducts
     *
     * @return \Generated\Shared\Transfer\AvailabilityStockTransfer
     */
    protected function loadAvailabilityGuiStockTransfer($sku, array $stockProducts)
    {
        $AvailabilityGuiStockTransfer = new AvailabilityStockTransfer();
        $AvailabilityGuiStockTransfer->setSku($sku);

        foreach ($stockProducts as $stockProductTransfer) {
            $AvailabilityGuiStockTransfer->addStockProduct($stockProductTransfer);
        }

        return $AvailabilityGuiStockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityStockTransfer $AvailabilityStockTransfer
     *
     * @return void
     */
    protected function addEmptyStockType($AvailabilityStockTransfer)
    {
        $allStockType = $this->stockFacade->getAvailableStockTypes();

        foreach ($allStockType as $type) {
            if ($this->stockTypeExist($AvailabilityStockTransfer, $type)) {
                continue;
            }
            $stockProductTransfer = new StockProductTransfer();
            $stockProductTransfer->setStockType($type);
            $stockProductTransfer->setQuantity(0);

            $AvailabilityStockTransfer->addStockProduct($stockProductTransfer);
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
