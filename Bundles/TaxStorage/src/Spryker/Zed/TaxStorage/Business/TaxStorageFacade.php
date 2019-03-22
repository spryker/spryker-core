<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\TaxStorage\Business\TaxStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageEntityManager getEntityManager()
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStorageRepository getRepository()
 */
class TaxStorageFacade extends AbstractFacade implements TaxStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $taxSetIds
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
     * @param array $taxSetIds
     *
     * @return void
     */
    public function unpublishByTaxSetIds(array $taxSetIds): void
    {
        $this->getFactory()->createTaxStoragePublisher()->unpublishByTaxSetIds($taxSetIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $taxRateIds
     *
     * @return void
     */
    public function publishByTaxRateIds(array $taxRateIds): void
    {
        $this->getFactory()->createTaxStoragePublisher()->publishByTaxRateIds($taxRateIds);
    }
}
