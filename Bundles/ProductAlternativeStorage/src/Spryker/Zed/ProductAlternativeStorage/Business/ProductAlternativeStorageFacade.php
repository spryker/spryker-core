<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageBusinessFactory getFactory()
 */
class ProductAlternativeStorageFacade extends AbstractFacade implements ProductAlternativeStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishAlternative(array $productIds): void
    {
        $this->getFactory()->createProductAlternativePublisher()->publish($productIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishAbstractReplacements(array $productIds): void
    {
        $this->getFactory()
            ->createProductReplacementPublisher()
            ->publishAbstractReplacements($productIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishConcreteReplacements(array $productIds): void
    {
        $this->getFactory()
            ->createProductReplacementPublisher()
            ->publishConcreteReplacements($productIds);
    }
}
