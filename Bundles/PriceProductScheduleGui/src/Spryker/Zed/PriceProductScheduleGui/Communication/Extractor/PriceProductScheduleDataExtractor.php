<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Extractor;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatterInterface;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectStrategyResolverInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;

class PriceProductScheduleDataExtractor implements PriceProductScheduleDataExtractorInterface
{
    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatterInterface
     */
    protected $priceProductScheduleDataFormatter;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectStrategyResolverInterface
     */
    protected $priceProductScheduleRedirectStrategyResolver;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatterInterface $priceProductScheduleDataFormatter
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect\PriceProductScheduleRedirectStrategyResolverInterface $priceProductScheduleRedirectStrategyResolver
     */
    public function __construct(
        PriceProductScheduleGuiToStoreFacadeInterface $storeFacade,
        PriceProductScheduleDataFormatterInterface $priceProductScheduleDataFormatter,
        PriceProductScheduleRedirectStrategyResolverInterface $priceProductScheduleRedirectStrategyResolver
    ) {
        $this->storeFacade = $storeFacade;
        $this->priceProductScheduleDataFormatter = $priceProductScheduleDataFormatter;
        $this->priceProductScheduleRedirectStrategyResolver = $priceProductScheduleRedirectStrategyResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return string
     */
    public function extractTitleFromPriceProductScheduleTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): string {
        $priceProductScheduleTransfer->requirePriceProduct();
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();

        return $this->priceProductScheduleDataFormatter->formatTitle($priceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return string
     */
    public function extractTimezoneTextFromPriceProductScheduledTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): string {
        $priceProductScheduleTransfer->requireStore();
        $storeTransfer = $this->storeFacade->getStoreById(
            $priceProductScheduleTransfer->getStore()->getIdStore()
        );
        $timezone = $storeTransfer->getTimezone();

        return $this->priceProductScheduleDataFormatter->formatTimezoneText($timezone);
    }
}
