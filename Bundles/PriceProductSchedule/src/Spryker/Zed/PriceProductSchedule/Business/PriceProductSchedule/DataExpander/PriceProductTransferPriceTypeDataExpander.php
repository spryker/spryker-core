<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProductSchedule\Business\PriceType\PriceTypeFinderInterface;

class PriceProductTransferPriceTypeDataExpander extends PriceProductTransferAbstractDataExpander
{
    protected const ERROR_MESSAGE_PRICE_TYPE_NOT_FOUND = 'Price type was not found by provided sku %s';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceType\PriceTypeFinderInterface
     */
    protected $priceTypeFinder;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceType\PriceTypeFinderInterface $priceTypeFinder
     */
    public function __construct(PriceTypeFinderInterface $priceTypeFinder)
    {
        $this->priceTypeFinder = $priceTypeFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function expand(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceTypeTransfer = $this->priceTypeFinder
            ->findPriceTypeByName($priceProductTransfer->getPriceTypeName());

        if ($priceTypeTransfer === null) {
            return $priceProductTransfer;
        }

        return $priceProductTransfer
            ->setFkPriceType($priceTypeTransfer->getIdPriceType())
            ->setPriceTypeName($priceTypeTransfer->getName())
            ->setPriceType($priceTypeTransfer);
    }
}
