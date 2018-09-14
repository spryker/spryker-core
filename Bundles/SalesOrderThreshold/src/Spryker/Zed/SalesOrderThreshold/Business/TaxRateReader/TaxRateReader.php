<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\TaxRateReader;

use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToTaxFacadeInterface;
use Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface;

class TaxRateReader implements TaxRateReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToTaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToTaxFacadeInterface $taxFacade
     * @param \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface $repository
     */
    public function __construct(SalesOrderThresholdToTaxFacadeInterface $taxFacade, SalesOrderThresholdRepositoryInterface $repository)
    {
        $this->taxFacade = $taxFacade;
        $this->repository = $repository;
    }

    /**
     * @return float
     */
    public function getSalesOrderThresholdTaxRate(): float
    {
        $countryIso2Code = $this->taxFacade->getDefaultTaxCountryIso2Code() ?? SalesOrderThresholdConfig::DEFAULT_TAX_RATE_ISO2CODE;
        $taxRate = $this->repository->findMaxTaxRateByCountryIso2Code($countryIso2Code);
        if ($taxRate !== null) {
            return $taxRate;
        }

        return $this->taxFacade->getDefaultTaxRate();
    }
}
