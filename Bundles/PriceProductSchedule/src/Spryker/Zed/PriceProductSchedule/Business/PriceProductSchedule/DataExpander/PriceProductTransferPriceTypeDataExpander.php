<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\PriceProductExpandResultTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\Business\PriceType\PriceTypeFinderInterface;

class PriceProductTransferPriceTypeDataExpander extends PriceProductTransferAbstractDataExpander
{
    protected const ERROR_MESSAGE_PRICE_TYPE_NOT_FOUND = 'Price type was not found by provided sku %s';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceType\PriceTypeFinderInterface
     */
    protected $priceProductFinder;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceType\PriceTypeFinderInterface $priceProductFinder
     */
    public function __construct(
        PriceTypeFinderInterface $priceProductFinder
    ) {
        $this->priceProductFinder = $priceProductFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductExpandResultTransfer
     */
    public function expand(
        PriceProductTransfer $priceProductTransfer
    ): PriceProductExpandResultTransfer {
        $priceProductExpandResultTransfer = (new PriceProductExpandResultTransfer())
            ->setIsSuccess(false);

        $priceTypeTransfer = $this->priceProductFinder->findPriceTypeByName($priceProductTransfer->getPriceTypeName());

        if ($priceTypeTransfer === null) {
            $priceProductScheduleImportErrorTransfer = $this->createPriceProductScheduleListImportErrorTransfer(
                sprintf(
                    static::ERROR_MESSAGE_PRICE_TYPE_NOT_FOUND,
                    $priceProductTransfer->getPriceTypeName()
                )
            );

            return $priceProductExpandResultTransfer
                ->setError($priceProductScheduleImportErrorTransfer);
        }

        $priceProductTransfer
            ->setFkPriceType($priceTypeTransfer->getIdPriceType())
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer);

        return $priceProductExpandResultTransfer
            ->setPriceProduct($priceProductTransfer)
            ->setIsSuccess(true);
    }
}
