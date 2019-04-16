<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

interface PersistentCartShareClientInterface
{
    /**
     * Specification:
     * - Retrieves cart share options from CartShareOptionPluginInterface plugins.
     *
     * @api
     *
     * @return string[]
     */
    public function getCartShareOptions(): array;
}
