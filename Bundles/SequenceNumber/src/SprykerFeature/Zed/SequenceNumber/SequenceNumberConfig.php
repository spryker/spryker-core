<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class SequenceNumberConfig extends AbstractBundleConfig
{

    /**
     * @return SequenceNumberSettingsTransfer
     */
    public function getDefaultSettings()
    {
        $sequenceNumberSettings = new SequenceNumberSettingsTransfer();
        $sequenceNumberSettings->setName($this->getSequenceName());
        $sequenceNumberSettings->setIncrementMinimum($this->getNumberIncrementMin());
        $sequenceNumberSettings->setIncrementMaximum($this->getNumberIncrementMax());
        $sequenceNumberSettings->setMinimumNumber($this->getNumberMinimum());

        return $sequenceNumberSettings;
    }

    /**
     * @return int
     */
    public function getPaddingLength()
    {
        return 0;
    }

    /**
     * @return int
     */
    public function getNumberMinimum()
    {
        return 1;
    }

    /**
     * @return int
     */
    public function getNumberIncrementMin()
    {
        return 1;
    }

    /**
     * @return int
     */
    public function getNumberIncrementMax()
    {
        return 1;
    }

    /** @return string */
    public function getSequenceName()
    {
        return 'Sequence';
    }

}
