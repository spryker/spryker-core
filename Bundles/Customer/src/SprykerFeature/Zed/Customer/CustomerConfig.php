<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer;

use Generated\Shared\SequenceNumber\SequenceNumberSettingsInterface;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerEngine\Shared\Kernel\Store;

class CustomerConfig extends AbstractBundleConfig
{

    const NAME_CUSTOMER_REFERENCE = 'CustomerReference';

    /**
     * @return string
     */
    public function getHostYves()
    {
        return $this->get(SystemConfig::HOST_YVES);
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }

    /**
     * @return bool
     */
    public function isDevelopmentEnvironment()
    {
        return \SprykerFeature_Shared_Library_Environment::getInstance()->isDevelopment();
    }

    /**
     * @return bool
     */
    public function isStagingEnvironment()
    {
        return \SprykerFeature_Shared_Library_Environment::getInstance()->isStaging();
    }

    /**
     * @return int
     */
    public function getMinimumCustomerNumber()
    {
        return 100;
    }

    /**
     * @return int
     */
    public function getCustomerNumberIncrementMin()
    {
        return 23;
    }

    /**
     * @return int
     */
    public function getCustomerNumberIncrementMax()
    {
        return 42;
    }

    /**
     * @param string $token
     *
     * @return string
     */
    public function getCustomerPasswordRestoreTokenUrl($token)
    {
        return $this->getHostYves() . '/password/restore?token=' . $token;
    }

    /**
     * @param string $token
     *
     * @return string
     */
    public function getRegisterConfirmTokenUrl($token)
    {
        return $this->getHostYves() . '/register/confirm?token=' . $token;
    }

    /**
     * @return SequenceNumberSettingsInterface
     */
    public function getCustomerReferenceDefaults()
    {
        $sequenceNumberSettingsTransfer = new SequenceNumberSettingsTransfer();

        $sequenceNumberSettingsTransfer->setName(self::NAME_CUSTOMER_REFERENCE);

        $storeName = $this->getStoreName();
        $prefix = $storeName . $this->getUniqueIdentifierSeparator() . $this->getEnvironmentPrefix();
        $sequenceNumberSettingsTransfer->setPrefix($prefix);

        return $sequenceNumberSettingsTransfer;
    }

    /**
     * @return string
     */
    protected function getEnvironmentPrefix()
    {
        $environment = \SprykerFeature_Shared_Library_Environment::getInstance();

        if ($environment->isStaging()) {
            return 'S';
        }

        if ($environment->isDevelopment()) {
            return 'D' . $this->getUniqueIdentifierSeparator() . $this->getTimestamp();
        }

        return 'P';
    }

    /**
     * @return string
     */
    protected function getUniqueIdentifierSeparator()
    {
        return '-';
    }

    /**
     * @return string
     */
    protected function getTimestamp()
    {
        return (string) time();
    }

}
