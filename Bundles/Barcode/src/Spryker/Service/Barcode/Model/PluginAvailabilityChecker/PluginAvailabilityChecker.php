<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Barcode\Model\PluginAvailabilityChecker;

class PluginAvailabilityChecker implements PluginAvailabilityCheckerInterface
{
    /**
     * TODO: Temporary solution; will be extended in future
     *
     * @param string $pluginClassName
     *
     * @return bool
     */
    public function check(string $pluginClassName): bool
    {
        return class_exists($pluginClassName);
    }
}
