<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer;

use Generated\Shared\SequenceNumber\SequenceNumberSettingsInterface;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\SequenceNumber\SequenceNumberConstants;
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

        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = Store::getInstance()->getStoreName();
        $sequenceNumberPrefixParts[] = $this->get(SequenceNumberConstants::ENVIRONMENT_PREFIX);
        $prefix = implode($this->getUniqueIdentifierSeparator(), $sequenceNumberPrefixParts);
        $sequenceNumberSettingsTransfer->setPrefix($prefix);

        return $sequenceNumberSettingsTransfer;
    }

    /**
     * @return string
     */
    protected function getUniqueIdentifierSeparator()
    {
        return '-';
    }

}
