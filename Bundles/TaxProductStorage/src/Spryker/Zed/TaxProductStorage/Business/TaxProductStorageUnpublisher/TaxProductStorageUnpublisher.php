<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Business\TaxProductStorageUnpublisher;

use Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface;

class TaxProductStorageUnpublisher implements TaxProductStorageUnpublisherInterface
{
    /**
     * @var \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface
     */
    protected $taxProductStorageEntityManager;

    /**
     * @param \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface $taxProductStorageEntityManager
     */
    public function __construct(TaxProductStorageEntityManagerInterface $taxProductStorageEntityManager)
    {
        $this->taxProductStorageEntityManager = $taxProductStorageEntityManager;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds): void
    {
        $this->taxProductStorageEntityManager->deleteTaxProductStoragesByProductAbstractIds($productAbstractIds);
    }
}
