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
     * @param int[] $idProduct
     *
     * @return void
     */
    public function publishAlternative(array $idProduct): void
    {
        $this->getFactory()->createProductAlternativePublisher()->publish($idProduct);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $idProduct
     *
     * @return void
     */
    public function publishReplacement(array $idProduct): void
    {
        $this->getFactory()->createProductReplacementPublisher()->publish($idProduct);
    }
}
