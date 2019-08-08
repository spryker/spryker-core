<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\TaxStorage\Business\TaxStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepositoryInterface getRepository()
 */
class TaxStorageFacade extends AbstractFacade implements TaxStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $taxSetIds
     *
     * @return void
     */
    public function publishByTaxSetIds(array $taxSetIds): void
    {
        $this->getFactory()->createTaxStoragePublisher()->publishByTaxSetIds($taxSetIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $taxSetIds
     *
     * @return void
     */
    public function unpublishByTaxSetIds(array $taxSetIds): void
    {
        $this->getFactory()->createTaxStorageUnpublisher()->unpublishByTaxSetIds($taxSetIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $taxRateIds
     *
     * @return void
     */
    public function publishByTaxRateIds(array $taxRateIds): void
    {
        $this->getFactory()->createTaxStoragePublisher()->publishByTaxRateIds($taxRateIds);
    }
}
