<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Persistence;

/**
 * @method \Spryker\Zed\Currency\Persistence\CurrencyPersistenceFactory getFactory()
 */
interface CurrencyQueryContainerInterface
{
    /**
     * @api
     *
     * @param string $isoCode
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery
     */
    public function queryCurrencyByIsoCode($isoCode);

    /**
     * @api
     *
     * @param int $idCurrency
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery
     */
    public function queryCurrencyByIdCurrency($idCurrency);

    /**
     * @api
     *
     * @param array $isoCodes
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery
     */
    public function queryCurrenciesByIsoCodes(array $isoCodes);
}
