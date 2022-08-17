<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Communication\Plugin\PriceProduct;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\OrphanPriceProductStoreRemovalVoterPluginInterface;

/**
 * @method \Spryker\Zed\AclEntity\Business\AclEntityFacadeInterface getFacade()
 * @method \Spryker\Zed\AclEntity\Communication\AclEntityCommunicationFactory getFactory()
 * @method \Spryker\Zed\AclEntity\AclEntityConfig getConfig()
 */
class AclEntityOrphanPriceProductStoreRemovalVoterPlugin extends AbstractPlugin implements OrphanPriceProductStoreRemovalVoterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Enables removal of orphaned price product store records on save if `AclEntity` behavior is disabled.
     *
     * @api
     *
     * @return bool
     */
    public function isRemovalEnabled(): bool
    {
        return !$this->getFacade()->isActive();
    }
}
