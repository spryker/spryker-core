<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Currency\Persistence\CurrencyPersistenceFactory getFactory()
 */
class CurrencyRepository extends AbstractRepository implements CurrencyRepositoryInterface
{
    /**
     * @var \Spryker\Zed\Currency\Persistence\CurrencyMapperInterface
     */
    protected $currencyMapper;

    public function __construct()
    {
        $this->currencyMapper = $this->getFactory()->createCurrencyMapper();
    }

    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    public function findCurrencyByIsoCode(string $isoCode): ?CurrencyTransfer
    {
        $currencyEntity = $this->getFactory()
            ->createCurrencyQuery()
            ->filterByCode($isoCode)
            ->findOne();

        if ($currencyEntity === null) {
            return null;
        }

        return $this->currencyMapper->mapCurrencyEntityToCurrencyTransfer(
            $currencyEntity,
            $this->createCurrencyTransfer()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function createCurrencyTransfer(): CurrencyTransfer
    {
        return new CurrencyTransfer();
    }
}
