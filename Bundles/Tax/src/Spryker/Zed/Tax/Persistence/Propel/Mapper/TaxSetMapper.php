<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxSet;

class TaxSetMapper implements TaxSetMapperInterface
{
    /**
     * @var \Spryker\Zed\Tax\Persistence\Propel\Mapper\TaxRateMapperInterface
     */
    protected $taxRateMapper;

    /**
     * @param \Spryker\Zed\Tax\Persistence\Propel\Mapper\TaxRateMapperInterface $taxRateMapper
     */
    public function __construct(TaxRateMapperInterface $taxRateMapper)
    {
        $this->taxRateMapper = $taxRateMapper;
    }

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function mapTaxSetEntityToTaxSetTransfer(
        SpyTaxSet $taxSetEntity,
        TaxSetTransfer $taxSetTransfer
    ): TaxSetTransfer {
        $taxSetTransfer = $taxSetTransfer->fromArray($taxSetEntity->toArray());

        foreach ($taxSetEntity->getSpyTaxRates() as $taxRateEntity) {
            $taxRateTransfer = $this->taxRateMapper->mapTaxRateEntityToTaxRateTransfer(
                $taxRateEntity,
                new TaxRateTransfer()
            );

            $taxSetTransfer->addTaxRate($taxRateTransfer);
        }

        return $taxSetTransfer;
    }
}
