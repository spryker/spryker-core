<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Redirect;

use Exception;
use Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer;

class PriceProductScheduleRedirectStrategyResolver implements PriceProductScheduleRedirectStrategyResolverInterface
{
    public const KEY_SCHEDULE_LIST = 'schedule_list';
    public const KEY_ABSTRACT_PRODUCT = 'abstract_product';
    public const KEY_CONCRETE_PRODUCT = 'concrete_product';
    protected const EXCEPTION_MESSAGE = 'Container is not valid';
    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Communication\Redirect\PriceProductScheduleRedirectInterface[]
     */
    protected $priceProductScheduleStrategyContainer;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Communication\Redirect\PriceProductScheduleRedirectInterface[] $priceProductScheduleStrategyContainer
     */
    public function __construct(array $priceProductScheduleStrategyContainer)
    {
        $this->priceProductScheduleStrategyContainer = $priceProductScheduleStrategyContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer
     */
    public function resolve(PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer): PriceProductScheduleRedirectTransfer
    {
        $this->validateContainer();

        $idPriceProductScheduleList = $priceProductScheduleRedirectTransfer->getIdPriceProductScheduleList();
        if ($idPriceProductScheduleList !== null) {
            return $this->priceProductScheduleStrategyContainer[static::KEY_SCHEDULE_LIST]
                ->makeRedirectUrl($priceProductScheduleRedirectTransfer);
        }

        $idProductAbstract = $priceProductScheduleRedirectTransfer->getIdProductAbstract();
        $idProductConcrete = $priceProductScheduleRedirectTransfer->getIdProduct();

        if ($idProductConcrete !== null && $idProductAbstract !== null) {
            return $this->priceProductScheduleStrategyContainer[static::KEY_CONCRETE_PRODUCT]
                ->makeRedirectUrl($priceProductScheduleRedirectTransfer);
        }

        return $this->priceProductScheduleStrategyContainer[static::KEY_ABSTRACT_PRODUCT]
            ->makeRedirectUrl($priceProductScheduleRedirectTransfer);
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    protected function validateContainer(): void
    {
        if (isset(
            $this->priceProductScheduleStrategyContainer[static::KEY_ABSTRACT_PRODUCT],
            $this->priceProductScheduleStrategyContainer[static::KEY_CONCRETE_PRODUCT],
            $this->priceProductScheduleStrategyContainer[static::KEY_SCHEDULE_LIST]
        )) {
            return;
        }

        throw new Exception(static::EXCEPTION_MESSAGE);
    }
}
