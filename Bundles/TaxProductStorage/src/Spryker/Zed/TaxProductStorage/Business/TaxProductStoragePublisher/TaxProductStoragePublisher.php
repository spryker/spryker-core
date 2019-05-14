<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Business\TaxProductStoragePublisher;

use Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface;
use Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface;

class TaxProductStoragePublisher implements TaxProductStoragePublisherInterface
{
    /**
     * @var \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface
     */
    protected $taxProductStorageRepository;

    /**
     * @var \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface
     */
    protected $taxProductStorageEntityManager;

    /**
     * @param \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface $taxProductStorageRepository
     * @param \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface $taxProductStorageEntityManager
     */
    public function __construct(
        TaxProductStorageRepositoryInterface $taxProductStorageRepository,
        TaxProductStorageEntityManagerInterface $taxProductStorageEntityManager
    ) {
        $this->taxProductStorageRepository = $taxProductStorageRepository;
        $this->taxProductStorageEntityManager = $taxProductStorageEntityManager;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        $spyTaxProductStorages = $this->taxProductStorageRepository
            ->getTaxProductTransfersFromProductAbstractsByIds($productAbstractIds);

        $this->taxProductStorageEntityManager->updateTaxProductStorages($spyTaxProductStorages);
    }
}
