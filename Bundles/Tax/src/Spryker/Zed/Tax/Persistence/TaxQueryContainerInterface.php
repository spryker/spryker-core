<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface TaxQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxRateQuery
     */
    public function queryTaxRate($id);

    /**
     * @api
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxRateQuery
     */
    public function queryAllTaxRates();

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSet($id);

    /**
     * @api
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryAllTaxSets();

    /**
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     *
     * @return $this
     */
    public function joinTaxRates(ModelCriteria $expandableQuery);

    /**
     * @api
     *
     * @param int[] $allIdProductAbstracts
     * @param string $countryIso2Code
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSetByIdProductAbstractAndCountryIso2Code(array $allIdProductAbstracts, $countryIso2Code);

}
