<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Propel\Runtime\Collection\ObjectCollection;
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
     * @param string[] $isoCodes
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    public function getCurrencyTransfersByIsoCodes(array $isoCodes): array
    {
        $currencyEntities = $this->getFactory()
            ->createCurrencyQuery()
            ->filterByCode_In($isoCodes)
            ->find();

        if ($currencyEntities->count() === 0) {
            return [];
        }

        return $this->mapCurrencyEntitiesToCurrencyTransfers($currencyEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Currency\Persistence\SpyCurrency[] $currencyEntities
     *
     * @return array
     */
    protected function mapCurrencyEntitiesToCurrencyTransfers(ObjectCollection $currencyEntities): array
    {
        $currencyTransfers = [];
        foreach ($currencyEntities as $currencyEntity) {
            $currencyTransfers[] = $this->currencyMapper->mapCurrencyEntityToCurrencyTransfer($currencyEntity, $this->createCurrencyTransfer());
        }

        return $currencyTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function createCurrencyTransfer(): CurrencyTransfer
    {
        return new CurrencyTransfer();
    }
}
