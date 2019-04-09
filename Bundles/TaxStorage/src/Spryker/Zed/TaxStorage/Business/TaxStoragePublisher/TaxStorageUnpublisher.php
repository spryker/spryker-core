<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business\TaxStoragePublisher;

use Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface;

class TaxStorageUnpublisher implements TaxStorageUnpublisherInterface
{
    /**
     * @var \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface
     */
    protected $taxStorageRepository;

    /**
     * @param \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface $entityManager
     */
    public function __construct(TaxStorageEntityManagerInterface $entityManager)
    {
        $this->taxStorageEntityManager = $entityManager;
    }

    /**
     * @param int[] $taxSetIds
     *
     * @return void
     */
    public function unpublishByTaxSetIds(array $taxSetIds): void
    {
        $spyTaxSetStorages = $this->taxStorageEntityManager
            ->deleteTaxSetStoragesByIds($taxSetIds);
    }
}
