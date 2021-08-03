<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Communication\Plugin\AclEntity;

use Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityEnablerPluginInterface;

class AclEntityEnablerPlugin implements AclEntityEnablerPluginInterface
{
    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return true;
    }
}
