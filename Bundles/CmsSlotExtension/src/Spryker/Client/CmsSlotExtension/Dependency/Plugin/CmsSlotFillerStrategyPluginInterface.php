<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotExtension\Dependency\Plugin;

interface CmsSlotFillerStrategyPluginInterface
{
    /**
     * Specification:
     *  - Returns true if strategy can be used for the auto filling key.
     *
     * @api
     *
     * @param string $fillingKey
     *
     * @return bool
     */
    public function isApplicable(string $fillingKey): bool;

    /**
     * Specification:
     *  - Returns data which represents the key in the cms slot request.
     *
     * @api
     *
     * @param string $fillingKey
     *
     * @return mixed
     */
    public function fill(string $fillingKey);
}
