<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin;

interface SalesOrderThresholdFormFieldDependenciesPluginInterface
{
    /**
     * Specification:
     *  - Returns the threshold dependent field names using in form.
     *
     * @api
     *
     * @return array
     */
    public function getThresholdFieldDependentFieldNames(): array;
}
