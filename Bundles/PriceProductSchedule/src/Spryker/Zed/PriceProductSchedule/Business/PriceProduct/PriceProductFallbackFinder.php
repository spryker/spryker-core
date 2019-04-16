<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProduct;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;

class PriceProductFallbackFinder implements PriceProductFallbackFinderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected $priceProductScheduleConfig;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig $priceProductScheduleConfig
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(
        PriceProductScheduleConfig $priceProductScheduleConfig,
        PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
    ) {
        $this->priceProductScheduleConfig = $priceProductScheduleConfig;
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function findFallbackPriceProduct(PriceProductTransfer $priceProductTransfer): ?PriceProductTransfer
    {
        $fallbackPriceTypeName = $this->findFallbackPriceType($priceProductTransfer->getPriceTypeName());

        if ($fallbackPriceTypeName === null) {
            return null;
        }

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($priceProductTransfer->getSkuProduct())
            ->setPriceTypeName($fallbackPriceTypeName)
            ->setCurrencyIsoCode($priceProductTransfer->getMoneyValue()->getCurrency()->getCode());

        return $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);
    }

    /**
     * @param string $priceTypeName
     *
     * @return string|null
     */
    protected function findFallbackPriceType(string $priceTypeName): ?string
    {
        $fallBackPriceTypeList = $this->priceProductScheduleConfig->getFallbackPriceTypeList();

        return $fallBackPriceTypeList[$priceTypeName] ?? null;
    }
}
