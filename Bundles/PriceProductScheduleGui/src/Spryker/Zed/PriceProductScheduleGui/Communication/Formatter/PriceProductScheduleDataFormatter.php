<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Formatter;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToProductFacadeInterface;

class PriceProductScheduleDataFormatter implements PriceProductScheduleDataFormatterInterface
{
    protected const TITLE_PRODUCT_ABSTRACT_PATTERN = 'Edit Product Abstract: %s';
    protected const TITLE_PRODUCT_CONCRETE_PATTERN = 'Edit Product Concrete: %s';

    protected const REDIRECT_URL_PRODUCT_CONCRETE_PATTERN = '/product-management/edit/variant?id-product=%s&id-product-abstract=%s#tab-content-scheduled_prices';
    protected const REDIRECT_URL_PRODUCT_ABSTRACT_PATTERN = '/product-management/edit?id-product-abstract=%s#tab-content-scheduled_prices';
    protected const REDIRECT_URL_SCHEDULE_LIST_PATTERN = '/price-product-schedule-gui/edit-schedule-list?id-price-product-schedule-list=%s';

    protected const TIMEZONE_TEXT_PATTERN = 'The timezone used for the scheduled price will be <b>%s</b> as defined on the store selected';

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToProductFacadeInterface $productFacade
     */
    public function __construct(PriceProductScheduleGuiToProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function formatTitle(PriceProductTransfer $priceProductTransfer): string
    {
        $idProductAbstract = $priceProductTransfer->getIdProductAbstract();
        if ($idProductAbstract !== null) {
            return sprintf(static::TITLE_PRODUCT_ABSTRACT_PATTERN, $idProductAbstract);
        }

        $idProductConcrete = $priceProductTransfer->getIdProduct();

        return sprintf(static::TITLE_PRODUCT_CONCRETE_PATTERN, $idProductConcrete);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int|null $idPriceProductScheduleList
     *
     * @return string
     */
    public function formatRedirectUrl(
        PriceProductTransfer $priceProductTransfer,
        ?int $idPriceProductScheduleList
    ): string {
        if ($idPriceProductScheduleList !== null) {
            return sprintf(static::REDIRECT_URL_SCHEDULE_LIST_PATTERN, $idPriceProductScheduleList);
        }

        $idProductAbstract = $priceProductTransfer->getIdProductAbstract();
        $idProductConcrete = $priceProductTransfer->getIdProduct();

        if ($idProductConcrete !== null) {
            return $this->makeRedirectUrlByIdProductConcreteAndIdProductAbstract(
                $idProductConcrete
            );
        }

        return sprintf(static::REDIRECT_URL_PRODUCT_ABSTRACT_PATTERN, $idProductAbstract);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return string
     */
    protected function makeRedirectUrlByIdProductConcreteAndIdProductAbstract(
        int $idProductConcrete
    ): string {
        $idProductAbstract = $this->productFacade->findProductAbstractIdByConcreteId($idProductConcrete);

        if ($idProductAbstract === null) {
            return '/';
        }

        return sprintf(static::REDIRECT_URL_PRODUCT_CONCRETE_PATTERN, $idProductConcrete, $idProductAbstract);
    }

    /**
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatTimezoneText(?string $timezone): string
    {
        if ($timezone === null) {
            return '';
        }

        return sprintf(static::TIMEZONE_TEXT_PATTERN, $timezone);
    }
}
