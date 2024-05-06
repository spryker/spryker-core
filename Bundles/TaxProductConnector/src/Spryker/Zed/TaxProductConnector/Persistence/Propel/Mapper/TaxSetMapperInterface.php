<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\TaxSetTransfer;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Propel\Runtime\Collection\Collection;

interface TaxSetMapperInterface
{
    /**
     * @param \Orm\Zed\Tax\Persistence\SpyTaxSet $taxSetEntity
     *
     * @return \Generated\Shared\Transfer\TaxSetTransfer
     */
    public function mapTaxSetEntityToTaxSetTransfer(SpyTaxSet $taxSetEntity): TaxSetTransfer;

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Product\Persistence\SpyProductAbstract> $productAbstractEntities
     * @param array<int, \Generated\Shared\Transfer\TaxSetTransfer> $taxSetTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\TaxSetTransfer>
     */
    public function mapProductAbstractEntitiesToTaxSetTransfers(Collection $productAbstractEntities, array $taxSetTransfers = []): array;
}
