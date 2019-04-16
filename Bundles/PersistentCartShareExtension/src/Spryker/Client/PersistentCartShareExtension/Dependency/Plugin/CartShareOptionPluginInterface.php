<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShareExtension\Dependency\Plugin;

interface CartShareOptionPluginInterface
{
    /**
     * Specification:
     * - Returns share option key.
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string;
}
