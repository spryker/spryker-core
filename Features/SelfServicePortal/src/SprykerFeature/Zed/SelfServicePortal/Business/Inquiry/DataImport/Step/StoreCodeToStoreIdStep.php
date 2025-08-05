<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DataImport\Step;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DataImport\DataSet\SspInquiryDataSetInterface;

class StoreCodeToStoreIdStep implements DataImportStepInterface
{
    /**
     * @var array<int, \Generated\Shared\Transfer\StoreTransfer>
     */
    protected array $storeTransfers = [];

    public function __construct(protected StoreFacadeInterface $storeFacade)
    {
    }

    public function execute(DataSetInterface $dataSet): void
    {
        if (!$this->storeTransfers) {
            $this->storeTransfers = $this->storeFacade->getAllStores();
        }

        foreach ($this->storeTransfers as $storeTransfer) {
            if ($storeTransfer->getName() !== $dataSet[SspInquiryDataSetInterface::STORE]) {
                continue;
            }

            $dataSet[SspInquiryDataSetInterface::FK_STORE] = $storeTransfer->getIdStore();
        }
    }
}
