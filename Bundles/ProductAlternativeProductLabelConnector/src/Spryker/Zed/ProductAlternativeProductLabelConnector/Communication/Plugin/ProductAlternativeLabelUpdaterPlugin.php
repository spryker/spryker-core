<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\ProductAlternativeLabelUpdaterPluginInterface;

/**
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig getConfig()
 */
class ProductAlternativeLabelUpdaterPlugin extends AbstractPlugin implements ProductAlternativeLabelUpdaterPluginInterface
{
    /**
     * Specification:
     * - Returns a list of Product Label - Product Abstract relation to assign and deassign.
     * - The relation changes are based on presence of alternatives.
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
