<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin;

interface SalesOrderThresholdFormFieldDependenciesPluginInterface
{
    /**
     * Specification:
     *  - Returns the names of the fields that depend on the threshold in the form.
     *
     * @api
     *
     * @return string[]
     */
    public function getThresholdFieldDependentFieldNames(): array;
}
