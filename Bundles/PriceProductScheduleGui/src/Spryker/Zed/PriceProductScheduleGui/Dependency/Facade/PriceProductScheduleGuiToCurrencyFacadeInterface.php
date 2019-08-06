<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-01
 * Time: 17:31
 */

namespace Spryker\Zed\PriceProductScheduleGui\Dependency\Facade;

interface PriceProductScheduleGuiToCurrencyFacadeInterface
{
    /**
     * @throws \Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreWithCurrencyTransfer[]
     */
    public function getAllStoresWithCurrencies();
}
