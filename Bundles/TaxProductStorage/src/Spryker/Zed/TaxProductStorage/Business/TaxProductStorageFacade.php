<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\TaxProductStorage\Business\TaxProductStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStorageRepositoryInterface getRepository()
 */
class TaxProductStorageFacade extends AbstractFacade implements TaxProductStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createTaxProductStoragePublisher()
            ->publish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createTaxProductStorageUnpublisher()
            ->unpublish($productAbstractIds);
    }
}
