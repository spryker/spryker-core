<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductLabel\Dependency\Plugin\ProductLabelRelationUpdaterPluginInterface;

/**
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig getConfig()
 */
class ProductAlternativeLabelUpdaterPlugin extends AbstractPlugin implements ProductLabelRelationUpdaterPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges(): array
    {
        return $this->getFacade()->findProductLabelProductAbstractRelationChanges();
    }
}
