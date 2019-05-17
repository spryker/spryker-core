<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business\TaxStoragePublisher;

use Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface;
use Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface;
use Spryker\Zed\TaxStorage\TaxStorageConfig;

class TaxStoragePublisher implements TaxStoragePublisherInterface
{
    /**
     * @var \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface
     */
    protected $taxStorageRepository;

    /**
     * @var \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface
     */
    protected $taxStorageEntityManager;

    /**
     * @var \Spryker\Zed\TaxStorage\TaxStorageConfig
     */
    protected $taxStorageConfig;

    /**
     * @param \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface $taxStorageRepository
     * @param \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface $taxStorageEntityManager
     * @param \Spryker\Zed\TaxStorage\TaxStorageConfig $taxStorageConfig
     */
    public function __construct(
        TaxStorageRepositoryInterface $taxStorageRepository,
        TaxStorageEntityManagerInterface $taxStorageEntityManager,
        TaxStorageConfig $taxStorageConfig
    ) {
        $this->taxStorageRepository = $taxStorageRepository;
        $this->taxStorageEntityManager = $taxStorageEntityManager;
        $this->taxStorageConfig = $taxStorageConfig;
    }

    /**
     * @param int[] $taxSetIds
     *
     * @return void
     */
    public function publishByTaxSetIds(array $taxSetIds): void
    {
        $this->taxStorageEntityManager->saveTaxSetStorage(
            $this->taxStorageRepository->findTaxSetsByIds($taxSetIds)
        );
    }

    /**
     * @param int[] $taxRateIds
     *
     * @return void
     */
    public function publishByTaxRateIds(array $taxRateIds): void
    {
        $this->publishByTaxSetIds(
            $this->taxStorageRepository->findTaxSetIdsByTaxRateIds($taxRateIds)
        );
    }
}
