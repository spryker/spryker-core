<?php

namespace SprykerFeature\Zed\Customer\Business;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface;
use SprykerFeature\Zed\Customer\Dependency\Plugin\RegistrationTokenSenderPluginInterface;

class CustomerSettings
{
    /** @var AutoCompletion */
    protected $locator;

    public function __construct(LocatorLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @return PasswordRestoredConfirmationSenderPluginInterface[]
     */
    public function getPasswordRestoredConfirmationSenders()
    {
        return [];
    }

    /**
     * @return PasswordRestoreTokenSenderPluginInterface[]
     */
    public function getPasswordRestoreTokenSenders()
    {
        return [];
    }

    /**
     * @return RegistrationTokenSenderPluginInterface[]
     */
    public function getRegistrationTokenSenders()
    {
        return [];
    }
}
