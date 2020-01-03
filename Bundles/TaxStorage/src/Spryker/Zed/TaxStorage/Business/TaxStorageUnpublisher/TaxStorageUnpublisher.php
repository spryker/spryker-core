<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business\TaxStorageUnpublisher;

use Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface;

class TaxStorageUnpublisher implements TaxStorageUnpublisherInterface
{
    /**
     * @var \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface
     */
    protected $taxStorageEntityManager;

    /**
     * @param \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface $taxStorageEntityManager
     */
    public function __construct(TaxStorageEntityManagerInterface $taxStorageEntityManager)
    {
        $this->taxStorageEntityManager = $taxStorageEntityManager;
    }

    /**
     * @param int[] $taxSetIds
     *
     * @return void
     */
    public function unpublishByTaxSetIds(array $taxSetIds): void
    {
        $this->taxStorageEntityManager->deleteTaxSetStoragesByIds($taxSetIds);
    }
}
