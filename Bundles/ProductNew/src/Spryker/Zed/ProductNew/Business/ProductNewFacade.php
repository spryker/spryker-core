<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductNew\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductNew\Business\ProductNewBusinessFactory getFactory()
 */
class ProductNewFacade extends AbstractFacade implements ProductNewFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges()
    {
        return $this->getFactory()
            ->createProductAbstractRelationReader()
            ->findProductLabelProductAbstractRelationChanges();
    }
}
