<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Communication\Plugin\PasswordPolicy;

use Generated\Shared\Transfer\CustomerPasswordPolicyResultTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerPasswordPolicyPluginInterface;

/**
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class CustomerPasswordBlacklistPolicyPlugin extends AbstractPlugin implements CustomerPasswordPolicyPluginInterface
{
    private const CUSTOMER_POLICY_PLUGIN_NAME = 'blacklist';

    /**
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
        $resultTransfer = $this->getFactory()->createPasswordPolicyValidator()->checkBlacklist(
            $customerPassword,
            $resultTransfer,
            $config
        );

        return $resultTransfer;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getName(): string
    {
        return self::CUSTOMER_POLICY_PLUGIN_NAME;
    }
}
