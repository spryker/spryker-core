<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationPersistentCart;

use Generated\Shared\Transfer\PersistentCartChangeTransfer;

interface ProductConfigurationPersistentCartClientInterface
{
    /**
     * Specification:
     * - Requires `PersistentCartChangeTransfer::items::sku` to be set.
     * - Checks if the item has a configuration, if it does, the default configuration will not be set.
     * - Expands the provided persistent cart change transfer items with the corresponding product configuration instance.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function expandPersistentCartChangeWithProductConfigurationInstance(
        PersistentCartChangeTransfer $persistentCartChangeTransfer,
        array $params
    ): PersistentCartChangeTransfer;
}
