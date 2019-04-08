<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business\TaxStoragePublisher;

use Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface;
use Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface;

class TaxStorageUnpublisher implements TaxStorageUnpublisherInterface
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
     * @param \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface $taxStorageRepository
     * @param \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface $entityManager
     */
    public function __construct(
        TaxStorageRepositoryInterface $taxStorageRepository,
        TaxStorageEntityManagerInterface $entityManager
    ) {
        $this->taxStorageRepository = $taxStorageRepository;
        $this->taxStorageEntityManager = $entityManager;
    }

    /**
     * @param int[] $taxSetIds
     *
     * @return void
     */
    public function unpublishByTaxSetIds(array $taxSetIds): void
    {
        $spyTaxSetStorages = $this->taxStorageRepository
            ->deleteTaxSetStoragesByIds($taxSetIds);
    }
}
