<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Plugin\PasswordPolicy;

use Generated\Shared\Transfer\CustomerResponseTransfer;
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
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param string[] $config
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validate(
        string $customerPassword,
        CustomerResponseTransfer $customerResponseTransfer,
        array $config
    ): CustomerResponseTransfer {
        $customerResponseTransfer = $this->getFactory()->createPasswordPolicyValidator()->validateBlacklist(
            $customerPassword,
            $customerResponseTransfer,
            $config
        );

        return $customerResponseTransfer;
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
