<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductNew\Business;

interface ProductNewFacadeInterface
{

    /**
     * Specification:
     * - Returns the name of the "new" product label from bundle configuration.
     *
     * @api
     *
     * @return string
     */
    public function getLabelNewName();

    /**
     * Specification:
     * - Returns a list of Product Label - Product Abstract relation to assign and deassign.
     * - The relation changes are based on the "new from" and "new to" attributes of the products.
     * - Products that haven't got the "new" label yet and the current time is between their "new from-to" date range, are
     * considered to have the label assigned.
     * - Products that already got the "new" label and the current time is greater then their "new to" date, are considered
     * to have the label removed.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges();

}
