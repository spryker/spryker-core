<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence\Mapper;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetStorageTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxRate;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage;

interface TaxStorageMapperInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxRate $taxRateEntity
     * @param \Generated\Shared\Transfer\TaxRateTransfer $taxRateTransfer
     *
     * @return \Generated\Shared\Transfer\TaxRateTransfer
     */
    public function mapTaxRateEntityToTransfer(SpyTaxRate $taxRateEntity, TaxRateTransfer $taxRateTransfer): TaxRateTransfer;

    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity
     * @param \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function mapTaxSetEntityToTransfer(SpyTaxSet $taxSetEntity, TaxSetTransfer $taxSetTransfer): TaxSetTransfer;

    /**
     * @param \Spryker\Zed\TaxStorage\Persistence\Mapper\TaxSetStorageTransfer $taxSetStorageTransfer
     * @param \Spryker\Zed\TaxStorage\Persistence\Mapper\SpyTaxSetStorage $spyTaxSetStorage
     *
     * @return \Spryker\Zed\TaxStorage\Persistence\Mapper\SpyTaxSetStorage
     */
    public function mapTaxSetStorageTransferToEntity(TaxSetStorageTransfer $taxSetStorageTransfer, SpyTaxSetStorage $spyTaxSetStorage): SpyTaxSetStorage;

    /**
     * @param \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage $spyTaxSetStorage
     * @param \Generated\Shared\Transfer\TaxSetStorageTransfer $taxSetStorageTransfer
     *
     * @return \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage
     */
    public function mapTaxSetStorageEntityToTransfer(SpyTaxSetStorage $spyTaxSetStorage, TaxSetStorageTransfer $taxSetStorageTransfer): TaxSetStorageTransfer;

}
