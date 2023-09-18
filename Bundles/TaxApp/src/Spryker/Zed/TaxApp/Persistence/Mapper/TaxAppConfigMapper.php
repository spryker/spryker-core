<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Persistence\Mapper;

use Generated\Shared\Transfer\TaxAppConfigCollectionTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Orm\Zed\TaxApp\Persistence\SpyTaxAppConfig;
use Propel\Runtime\Collection\ObjectCollection;

class TaxAppConfigMapper
{
    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfig $taxAppConfigEntity
     *
     * @return \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfig
     */
    public function mapTaxAppConfigTransferToTaxAppConfigEntity(
        TaxAppConfigTransfer $taxAppConfigTransfer,
        SpyTaxAppConfig $taxAppConfigEntity
    ): SpyTaxAppConfig {
        return $taxAppConfigEntity->fromArray($taxAppConfigTransfer->toArray());
    }

    /**
     * @param \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfig $spyTaxAppConfigTransfer
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppConfigTransfer
     */
    public function mapTaxAppConfigEntityToTaxAppConfigTransfer(
        SpyTaxAppConfig $spyTaxAppConfigTransfer,
        TaxAppConfigTransfer $taxAppConfigTransfer
    ): TaxAppConfigTransfer {
        return $taxAppConfigTransfer->fromArray($spyTaxAppConfigTransfer->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $taxAppConfigEntities
     * @param \Generated\Shared\Transfer\TaxAppConfigCollectionTransfer $taxAppConfigCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppConfigCollectionTransfer
     */
    public function mapTaxAppConfigEntitiesToTaxAppConfigCollectionTransfer(
        ObjectCollection $taxAppConfigEntities,
        TaxAppConfigCollectionTransfer $taxAppConfigCollectionTransfer
    ): TaxAppConfigCollectionTransfer {
        foreach ($taxAppConfigEntities as $taxAppConfigEntity) {
            $taxAppConfigCollectionTransfer->addTaxAppConfig(
                $this->mapTaxAppConfigEntityToTaxAppConfigTransfer($taxAppConfigEntity, new TaxAppConfigTransfer()),
            );
        }

        return $taxAppConfigCollectionTransfer;
    }
}
