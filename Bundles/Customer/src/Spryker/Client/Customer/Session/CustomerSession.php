<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Session;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Session\SessionClientInterface;

class CustomerSession implements CustomerSessionInterface
{
    public const SESSION_KEY = 'customer data';

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    private $sessionClient;

    /**
     * @var \Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface[]
     */
    protected $customerSessionGetPlugins;

    /**
     * @var \Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface[]
     */
    protected $customerSessionSetPlugins;

    /**
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     * @param \Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface[] $customerSessionGetPlugins
     * @param \Spryker\Client\Customer\Dependency\Plugin\CustomerSessionSetPluginInterface[] $customerSessionSetPlugins
     */
    public function __construct(
        SessionClientInterface $sessionClient,
        array $customerSessionGetPlugins = [],
        array $customerSessionSetPlugins = []
    ) {
        $this->sessionClient = $sessionClient;
        $this->customerSessionGetPlugins = $customerSessionGetPlugins;
        $this->customerSessionSetPlugins = $customerSessionSetPlugins;
    }

    /**
     * @return void
     */
    public function logout()
    {
        $this->sessionClient->remove(self::SESSION_KEY);
    }

    /**
     * @return bool
     */
    public function hasCustomer()
    {
        return $this->sessionClient->has(self::SESSION_KEY);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer()
    {
        $customerTransfer = $this->sessionClient->get(self::SESSION_KEY);

        if ($customerTransfer === null) {
            return null;
        }

        foreach ($this->customerSessionGetPlugins as $customerSessionGetPlugin) {
            $customerSessionGetPlugin->execute($customerTransfer);
        }

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function setCustomer(CustomerTransfer $customerTransfer)
    {
        $this->sessionClient->set(
            self::SESSION_KEY,
            $customerTransfer
        );

        foreach ($this->customerSessionSetPlugins as $customerSessionSetPlugin) {
            $customerSessionSetPlugin->execute($customerTransfer);
        }

        return $customerTransfer;
    }

    /**
     * @return void
     */
    public function markCustomerAsDirty()
    {
        if ($this->hasCustomer() !== false) {
            $this->getCustomer()->setIsDirty(true);
        }
    }
}
