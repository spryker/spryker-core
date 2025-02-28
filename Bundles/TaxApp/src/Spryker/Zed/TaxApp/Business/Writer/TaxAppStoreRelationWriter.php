<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Writer;

use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;
use Spryker\Zed\TaxApp\Business\Config\ConfigWriterInterface;
use Spryker\Zed\TaxApp\Persistence\TaxAppRepositoryInterface;

class TaxAppStoreRelationWriter implements TaxAppStoreRelationWriterInterface
{
    /**
     * @param \Spryker\Zed\TaxApp\Persistence\TaxAppRepositoryInterface $taxAppRepository
     * @param \Spryker\Zed\TaxApp\Business\Config\ConfigWriterInterface $configWriter
     */
    public function __construct(
        protected TaxAppRepositoryInterface $taxAppRepository,
        protected ConfigWriterInterface $configWriter
    ) {
    }

    /**
     * @return void
     */
    public function refreshTaxAppStoreRelations(): void
    {
        $taxAppConfigCollectionTransfer = $this->taxAppRepository
            ->getTaxAppConfigCollection(new TaxAppConfigCriteriaTransfer());

        foreach ($taxAppConfigCollectionTransfer->getTaxAppConfigs() as $taxAppConfigTransfer) {
            $this->configWriter->write($taxAppConfigTransfer);
        }
    }
}
