<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\TaxRateReader;

use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToTaxFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface;

class TaxRateReader implements TaxRateReaderInterface
{
    protected const DEFAULT_TAX_RATE_ISO2CODE = 'DE';

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToTaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToTaxFacadeInterface $taxFacade
     * @param \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface $repository
     */
    public function __construct(MinimumOrderValueToTaxFacadeInterface $taxFacade, MinimumOrderValueRepositoryInterface $repository)
    {
        $this->taxFacade = $taxFacade;
        $this->repository = $repository;
    }

    /**
     * @return float
     */
    public function getMinimumOrderValueTaxRate(): float
    {
        $countryIso2Code = $this->taxFacade->getDefaultTaxCountryIso2Code() ?? static::DEFAULT_TAX_RATE_ISO2CODE;
        $taxRate = $this->repository->findMaxTaxRateByIdTaxSetAndCountryIso2Code($countryIso2Code);
        if ($taxRate !== null) {
            return $taxRate;
        }

        return $this->taxFacade->getDefaultTaxRate();
    }
}
