<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Plugin\Customer;

use Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerTableActionExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class RemoveMultiFactorAuthCustomerTableActionExpanderPlugin extends AbstractPlugin implements CustomerTableActionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     * @param array<\Generated\Shared\Transfer\ButtonTransfer> $buttonTransfers
     *
     * @return array<\Generated\Shared\Transfer\ButtonTransfer>
     */
    public function execute(int $idCustomer, array $buttonTransfers): array
    {
        return $this->getFactory()
            ->createMultiFactorAuthButtonCreator()
            ->addRemoveMultiFactorAuthButton($idCustomer, $buttonTransfers);
    }
}
