<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class DynamicEntityConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns a list of tables that should not be used for dynamic entity configuration.
     *
     * @api
     *
     * @return array<string>
     */
    public function getDisallowedTables(): array
    {
        return [
            'spy_dynamic_entity_configuration',
        ];
    }
}
