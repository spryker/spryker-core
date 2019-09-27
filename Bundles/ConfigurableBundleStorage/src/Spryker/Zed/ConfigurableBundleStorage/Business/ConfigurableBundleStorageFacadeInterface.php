<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Business;

interface ConfigurableBundleStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes configurable bundle template changes to storage.
     *
     * @api
     *
     * @param int[] $configurableBundleTemplateIds
     *
     * @return void
     */
    public function publishConfigurableBundleTemplate(array $configurableBundleTemplateIds): void;
}
