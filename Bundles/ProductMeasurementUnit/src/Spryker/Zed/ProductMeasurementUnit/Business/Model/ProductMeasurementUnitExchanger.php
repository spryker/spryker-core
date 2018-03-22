<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model;

use Exception;
use Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;

class ProductMeasurementUnitExchanger implements ProductMeasurementUnitExchangerInterface
{
    /**
     * @var array
     */
    protected $exchangeCollection;

    /**
     * @param array $exchangeCollection
     */
    public function __construct(array $exchangeCollection)
    {
        $this->exchangeCollection = $exchangeCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer $exchangeDetailTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer
     */
    public function getExchangeDetail(ProductMeasurementUnitExchangeDetailTransfer $exchangeDetailTransfer)
    {
        $this->assertExchangeIsDefined($exchangeDetailTransfer->getFromCode(), $exchangeDetailTransfer->getToCode());

        $conversion = $this->exchangeCollection[$exchangeDetailTransfer->getFromCode()][$exchangeDetailTransfer->getToCode()];

        $exchangeDetailTransfer->setConversion($conversion);
        $exchangeDetailTransfer->setPrecision(
            SpyProductMeasurementUnitQuery::create()->findOneByCode($exchangeDetailTransfer->getFromCode())->getDefaultPrecision()
        );

        return $exchangeDetailTransfer;
    }

    /**
     * @param string $fromCode
     * @param string $toCode
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function assertExchangeIsDefined($fromCode, $toCode)
    {
        if (isset($this->exchangeCollection[$fromCode][$toCode])) {
            return;
        }

        throw new Exception(sprintf('The "%s" and "%s" product measurement unit codes are not exchangeable.', $fromCode, $toCode));
    }
}
