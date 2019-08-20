<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotExtension\Dependency\Plugin;

interface CmsSlotExternalDataProviderStrategyPluginInterface
{
    /**
     * Specification:
     *  - Returns true if strategy can be used for the external data key.
     *
     * @api
     *
     * @param string $dataKey
     *
     * @return bool
     */
    public function isApplicable(string $dataKey): bool;

    /**
     * Specification:
     *  - Returns data which represents the key in the CMS slot request.
     *
     * @api
     *
     * @param string $dataKey
     *
     * @return mixed
     */
    public function getDataForKey(string $dataKey);
}
