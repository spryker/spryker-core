<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorageExtension\Dependency\Plugin;

interface ProductConcreteRestrictionPluginInterface
{
    /**
     * Specification:
     * - Checks if product concrete is restricted.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isRestricted(int $idProductAbstract): bool;
}
