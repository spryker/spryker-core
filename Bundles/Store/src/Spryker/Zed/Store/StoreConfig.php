<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store;

use Spryker\Shared\Store\StoreConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class StoreConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isMultiStorePerZedEnabled()
    {
        return false;
    }

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
     * @return array<string, string>
     */
    public function getStoreNameReferenceMap(): array
    {
        if ($this->getConfig()->hasKey(StoreConstants::STORE_NAME_REFERENCE_MAP)) {
            return $this->get(StoreConstants::STORE_NAME_REFERENCE_MAP);
        }

        // To have BC with StoreReference configuration
        return $this->get('STORE_REFERENCE:STORE_NAME_REFERENCE_MAP', []);
    }
}
