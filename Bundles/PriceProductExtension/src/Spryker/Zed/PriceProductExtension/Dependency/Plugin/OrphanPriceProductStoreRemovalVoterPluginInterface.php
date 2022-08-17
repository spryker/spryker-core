<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductExtension\Dependency\Plugin;

/**
 * Provides an ability to vote against orphan price product store records removal.
 *
 * Use this plugin to disable removal of orphan price product store records on the fly
 * when saving a product price. It doesn't affect clearing up the orphaned records with the console
 * command `price-product-store:optimize`
 *
 * The first plugin in the stack, that votes against the removal, disables it. If a plugin votes
 * for removal, all other plugins in the stack are still checked and may vote against.
 */
interface OrphanPriceProductStoreRemovalVoterPluginInterface
{
    /**
     * Specification:
     *  - Defines if removal of orphan price product store records is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isRemovalEnabled(): bool;
}
