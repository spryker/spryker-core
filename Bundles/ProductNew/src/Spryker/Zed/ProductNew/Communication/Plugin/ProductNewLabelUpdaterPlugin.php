<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductNew\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductLabel\Dependency\Plugin\ProductLabelRelationUpdaterPluginInterface;

/**
 * @method \Spryker\Zed\ProductNew\Business\ProductNewFacadeInterface getFacade()
 */
class ProductNewLabelUpdaterPlugin extends AbstractPlugin implements ProductLabelRelationUpdaterPluginInterface
{
    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges()
    {
        return $this->getFacade()->findProductLabelProductAbstractRelationChanges();
    }
}
