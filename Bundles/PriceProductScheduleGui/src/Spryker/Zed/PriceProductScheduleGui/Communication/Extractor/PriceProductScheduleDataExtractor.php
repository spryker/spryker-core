<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Extractor;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatterInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;

class PriceProductScheduleDataExtractor implements PriceProductScheduleDataExtractorInterface
{
    protected const TITLE_PRODUCT_ABSTRACT_PATTERN = 'Edit Product Abstract: %s';
    protected const TITLE_PRODUCT_CONCRETE_PATTERN = 'Edit Product Concrete: %s';

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatterInterface
     */
    protected $priceProductScheduleDataFormatter;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\PriceProductScheduleDataFormatterInterface $priceProductScheduleDataFormatter
     */
    public function __construct(
        PriceProductScheduleGuiToStoreFacadeInterface $storeFacade,
        PriceProductScheduleDataFormatterInterface $priceProductScheduleDataFormatter
    ) {
        $this->storeFacade = $storeFacade;
        $this->priceProductScheduleDataFormatter = $priceProductScheduleDataFormatter;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
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
     * @deprecated Will be removed without replacement.
     *
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

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return string
     */
    public function extractTimezoneFromPriceProductScheduledTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): string {
        $priceProductScheduleTransfer->requireStore();
        $storeTransfer = $this->storeFacade->getStoreById(
            $priceProductScheduleTransfer->getStore()->getIdStore()
        );

        return $storeTransfer->getTimezone();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return array
     */
    public function extractTitleAndIdProductFromPriceProductScheduleTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): array {
        $priceProductScheduleTransfer->requirePriceProduct();
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();
        $idProductAbstract = $priceProductTransfer->getIdProductAbstract();

        if ($idProductAbstract !== null) {
            return [static::TITLE_PRODUCT_ABSTRACT_PATTERN, $idProductAbstract];
        }

        $idProductConcrete = $priceProductTransfer->getIdProduct();

        return [static::TITLE_PRODUCT_CONCRETE_PATTERN, $idProductConcrete];
    }
}
