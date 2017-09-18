<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Model;

use Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException;
use Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreInterface;
use Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface;

class CurrencyReader implements CurrencyReaderInterface
{

    /**
     * @var \Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface
     */
    protected $currencyQueryContainer;

    /**
     * @var \Spryker\Zed\Currency\Business\Model\CurrencyMapperInterface
     */
    protected $currencyMapper;

    /**
     * @var \Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreInterface
     */
    protected $store;

    /**
     * @param \Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface $currencyQueryContainer
     * @param \Spryker\Zed\Currency\Business\Model\CurrencyMapperInterface $currencyMapper
     * @param \Spryker\Zed\Currency\Dependency\Facade\CurrencyToStoreInterface $store
     */
    public function __construct(
        CurrencyQueryContainerInterface $currencyQueryContainer,
        CurrencyMapperInterface $currencyMapper,
        CurrencyToStoreInterface $store
    ) {

        $this->currencyQueryContainer = $currencyQueryContainer;
        $this->currencyMapper = $currencyMapper;
        $this->store = $store;
    }

    /**
     * @param int $idCurrency
     *
     * @throws \Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getByIdCurrency($idCurrency)
    {
        $currencyEntity = $this->currencyQueryContainer
            ->queryCurrencyByIdCurrency($idCurrency)
            ->findOne();

        if (!$currencyEntity) {
            throw new CurrencyNotFoundException(
                sprintf('Currency with id "%s" not found.', $idCurrency)
            );
        }

        return $this->currencyMapper->mapEntityToTransfer($currencyEntity);
    }

    /**
     * @throws \Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    public function getStoreCurrencies()
    {
        $storeCurrencyIsoCodes = $this->store->getCurrencyIsoCodes();
        $currencyCollection = $this->currencyQueryContainer->queryCurrenciesByIsoCodes($storeCurrencyIsoCodes);

        if (count($currencyCollection) === 0) {
            throw new CurrencyNotFoundException(
                sprintf(
                    "There is no currencyCollection configured for current store, 
                    make sure you have currency iso codes provided in 'currencyIsoCodes' array in current stores.php config."
                )
            );
        }

        $currencies = [];
        foreach ($currencyCollection as $currencyEntity) {
            $currencies[] = $this->currencyMapper->mapEntityToTransfer($currencyEntity);
        }
        return $currencies;
    }

}
