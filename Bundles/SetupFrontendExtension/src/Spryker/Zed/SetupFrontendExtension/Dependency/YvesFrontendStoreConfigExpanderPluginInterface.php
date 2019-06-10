<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontendExtension\Dependency;

interface YvesFrontendStoreConfigExpanderPluginInterface
{
    /**
     * Specification:
     * -
     *
     * @api
     *
     * @param array $storeConfigData
     *
     * @return array
     */
    public function expand(array $storeConfigData): array;
}
