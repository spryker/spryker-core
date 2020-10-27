<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Plugin\PasswordPolicy;

use Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerPasswordPolicyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\CustomerConfig getConfig()
 */
class CustomerPasswordBlacklistPolicyPlugin extends AbstractPlugin implements CustomerPasswordPolicyPluginInterface
{
    private const CUSTOMER_POLICY_PLUGIN_NAME = 'blacklist';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $customerPassword
     * @param \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer $resultTransfer
     * @param string[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer
     */
    public function validate(
        string $customerPassword,
        CustomerPasswordPolicyResultTransfer $resultTransfer,
        array $config
    ): CustomerPasswordPolicyResultTransfer {
        $resultTransfer = $this->getFactory()->createPasswordPolicyValidator()->validateBlacklist(
            $customerPassword,
            $resultTransfer,
            $config
        );

        return $resultTransfer;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return self::CUSTOMER_POLICY_PLUGIN_NAME;
    }
}
