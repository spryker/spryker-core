<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Dependency\Plugin;

interface ProductLabelRelationUpdaterPluginInterface
{

    /**
     * Specification:
     * - Returns a list of Product Label - Product Abstract relation to assign and deassign.
     * - The results are used to persist product label relation changes into database.
     * - The plugin is called when the ProductLabelRelationUpdaterConsole command is executed.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges();

}
