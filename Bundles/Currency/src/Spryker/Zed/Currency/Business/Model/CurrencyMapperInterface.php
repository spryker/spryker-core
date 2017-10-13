<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Model;

use Generated\Shared\Transfer\CurrencyTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrency;

interface CurrencyMapperInterface
{
    /**
     * @param \Orm\Zed\Currency\Persistence\SpyCurrency $currencyEntity
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function mapEntityToTransfer(SpyCurrency $currencyEntity);

    /**
     * @param \Orm\Zed\Currency\Persistence\SpyCurrency $currencyEntity
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrency
     */
    public function mapTransferToEntity(SpyCurrency $currencyEntity, CurrencyTransfer $currencyTransfer);
}
