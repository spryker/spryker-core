<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Reader;

use Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface;

class CurrencyBulkReader implements CurrencyBulkReaderInterface
{
    /**
     * @var \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface
     */
    protected $currencyRepository;

    /**
     * @param \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface $currencyRepository
     */
    public function __construct(CurrencyRepositoryInterface $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @param string[] $isoCodes
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    public function getCurrencyTransfersByIsoCodes(array $isoCodes): array
    {
        return $this->currencyRepository->getCurrencyTransfersByIsoCodes($isoCodes);
    }
}
