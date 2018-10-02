<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Currency\Persistence\CurrencyPersistenceFactory getFactory()
 */
class CurrencyQueryContainer extends AbstractQueryContainer implements CurrencyQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $isoCode
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery
     */
    public function queryCurrencyByIsoCode($isoCode)
    {
        return $this->getFactory()
            ->createCurrencyQuery()
            ->filterByCode($isoCode);
    }

    /**
     * @api
     *
     * @param int $idCurrency
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery
     */
    public function queryCurrencyByIdCurrency($idCurrency)
    {
        return $this->getFactory()
            ->createCurrencyQuery()
            ->filterByPrimaryKey($idCurrency);
    }

    /**
     * @api
     *
     * @param array $isoCodes
     *
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery
     */
    public function queryCurrenciesByIsoCodes(array $isoCodes)
    {
        return $this->getFactory()
            ->createCurrencyQuery()
            ->filterByCode($isoCodes, Criteria::IN);
    }
}
