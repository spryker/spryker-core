<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AvailabilityStockTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityGui\Communication\Form\AvailabilityStockForm;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToLocaleInterface;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface;

class AvailabilityStockFormDataProvider
{
    /**
     * @var string
     */
    public const DATA_CLASS = 'data_class';

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface
     */
    protected $stockFacade;

    /**
     * @var \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeTransfer;

    /**
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockInterface $stockFacade
     * @param \Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToLocaleInterface $localeFacade
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     */
    public function __construct(
        AvailabilityGuiToStockInterface $stockFacade,
        AvailabilityGuiToLocaleInterface $localeFacade,
        StoreTransfer $storeTransfer
    ) {
        $this->stockFacade = $stockFacade;
        $this->localeFacade = $localeFacade;
        $this->storeTransfer = $storeTransfer;
    }

    /**
     * @param int $idProduct
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\AvailabilityStockTransfer
     */
    public function getData($idProduct, $sku)
    {
        $stockProducts = $this->stockFacade->findStockProductsByIdProductForStore($idProduct, $this->storeTransfer);

        $availabilityGuiStockTransfer = (new AvailabilityStockTransfer())->setSku($sku);

        if ($stockProducts) {
            $stockProducts = $this->sortProducts($stockProducts);
            $availabilityGuiStockTransfer = $this->loadAvailabilityGuiStockTransfer($availabilityGuiStockTransfer, $stockProducts);
        }

        $availabilityGuiStockTransfer = $this->addEmptyStockType($availabilityGuiStockTransfer);
        $availabilityGuiStockTransfer = $this->trimStockQuantities($availabilityGuiStockTransfer);

        return $availabilityGuiStockTransfer;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions()
    {
        return [
            static::DATA_CLASS => AvailabilityStockTransfer::class,
            AvailabilityStockForm::OPTION_LOCALE => $this->localeFacade->getCurrentLocaleName(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityStockTransfer $availabilityGuiStockTransfer
     * @param array<\Generated\Shared\Transfer\StockProductTransfer> $stockProducts
     *
     * @return \Generated\Shared\Transfer\AvailabilityStockTransfer
     */
    protected function loadAvailabilityGuiStockTransfer(AvailabilityStockTransfer $availabilityGuiStockTransfer, array $stockProducts = [])
    {
        foreach ($stockProducts as $stockProductTransfer) {
            $availabilityGuiStockTransfer->addStockProduct($stockProductTransfer);
        }

        return $availabilityGuiStockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityStockTransfer $availabilityStockTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityStockTransfer
     */
    protected function addEmptyStockType(AvailabilityStockTransfer $availabilityStockTransfer): AvailabilityStockTransfer
    {
        $allStockType = $this->stockFacade->getStockTypesForStore($this->storeTransfer);

        foreach ($allStockType as $type) {
            if ($this->stockTypeExist($availabilityStockTransfer, $type)) {
                continue;
            }

            $stockProductTransfer = (new StockProductTransfer())
                ->setStockType($type)
                ->setQuantity(0);

            $availabilityStockTransfer->addStockProduct($stockProductTransfer);
        }

        return $availabilityStockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityStockTransfer $availabilityStockTransfer
     *
     * @return \Generated\Shared\Transfer\AvailabilityStockTransfer
     */
    protected function trimStockQuantities(AvailabilityStockTransfer $availabilityStockTransfer): AvailabilityStockTransfer
    {
        foreach ($availabilityStockTransfer->getStocks() as $stockProductTransfer) {
            if ($stockProductTransfer->getQuantity() === null) {
                continue;
            }

            $stockProductTransfer->setQuantity(
                $stockProductTransfer->getQuantity()->trim(),
            );
        }

        return $availabilityStockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityStockTransfer $availabilityStockTransfer
     * @param string $type
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
            },
        );

        return $stockProducts;
    }
}
