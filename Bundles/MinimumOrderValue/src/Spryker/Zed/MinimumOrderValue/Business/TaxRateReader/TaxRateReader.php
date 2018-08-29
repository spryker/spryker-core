<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\TaxRateReader;

use Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig;
use Spryker\Zed\MinimumOrderValue\Dependency\Facade\MinimumOrderValueToTaxFacadeInterface;
use Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface;

class TaxRateReader implements TaxRateReaderInterface
{
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
        $countryIso2Code = $this->taxFacade->getDefaultTaxCountryIso2Code() ?? MinimumOrderValueConfig::DEFAULT_TAX_RATE_ISO2CODE;
        $taxRate = $this->repository->findMaxTaxRateByCountryIso2Code($countryIso2Code);
        if ($taxRate !== null) {
            return $taxRate;
        }

        return $this->taxFacade->getDefaultTaxRate();
    }
}
