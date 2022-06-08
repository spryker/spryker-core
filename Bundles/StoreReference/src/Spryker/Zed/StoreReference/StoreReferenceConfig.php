<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreReference;

use Spryker\Shared\StoreReference\StoreReferenceConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class StoreReferenceConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @example
     * [
     *     "DE": "dev-DE",
     *     "AT": "dev-AT",
     *     "STORE_NAME_A": "STORE_REFERENCE_A"
     * ]
     *
     * @return array<mixed>
     */
    public function getStoreNameReferenceMap(): array
    {
        return $this->get(StoreReferenceConstants::STORE_NAME_REFERENCE_MAP, []);
    }
}
