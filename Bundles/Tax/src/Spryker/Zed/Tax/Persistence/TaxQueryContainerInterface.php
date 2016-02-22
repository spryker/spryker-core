<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface TaxQueryContainerInterface
{

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxRateQuery
     */
    public function queryTaxRate($id);

    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxRateQuery
     */
    public function queryAllTaxRates();

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSet($id);

    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryAllTaxSets();

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     *
     * @return $this
     */
    public function joinTaxRates(ModelCriteria $expandableQuery);

}
