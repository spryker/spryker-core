<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Business\TaxRateReader;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToStoreFacadeInterface;
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
     * @var \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToStoreFacadeInterface
     */
    protected SalesOrderThresholdToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToTaxFacadeInterface $taxFacade
     * @param \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdRepositoryInterface $repository
     * @param \Spryker\Zed\SalesOrderThreshold\Dependency\Facade\SalesOrderThresholdToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        SalesOrderThresholdToTaxFacadeInterface $taxFacade,
        SalesOrderThresholdRepositoryInterface $repository,
        SalesOrderThresholdToStoreFacadeInterface $storeFacade
    ) {
        $this->taxFacade = $taxFacade;
        $this->repository = $repository;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return float
     */
    public function getSalesOrderThresholdTaxRate(?StoreTransfer $storeTransfer = null): float
    {
        $countryIso2Code = $this->getCountryIso2Code($storeTransfer);
        $taxRate = $this->repository->findMaxTaxRateByCountryIso2Code($countryIso2Code);
        if ($taxRate !== null) {
            return $taxRate;
        }

        return $this->taxFacade->getDefaultTaxRate();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return string
     */
    protected function getCountryIso2Code(?StoreTransfer $storeTransfer = null): string
    {
        if ($storeTransfer !== null) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());
            $countries = $storeTransfer->getCountries();

            if ($countries) {
                return reset($countries);
            }
        }

        return $this->taxFacade->getDefaultTaxCountryIso2Code();
    }
}
