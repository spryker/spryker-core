<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber;

use Generated\Shared\SequenceNumber\SequenceNumberSettingsInterface;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class SequenceNumberConfig extends AbstractBundleConfig
{

    /**
     * @param SequenceNumberSettingsInterface|null $settings
     *
     * @return SequenceNumberSettingsTransfer
     */
    public function getDefaultSettings(SequenceNumberSettingsInterface $settings = null)
    {
        $defaultSettings = new SequenceNumberSettingsTransfer();
        $defaultSettings->setName($this->getSequenceName());
        $defaultSettings->setIncrementMinimum($this->getNumberIncrementMin());
        $defaultSettings->setIncrementMaximum($this->getNumberIncrementMax());
        $defaultSettings->setMinimumNumber($this->getNumberMinimum());

        if ($settings === null) {
            return $defaultSettings;
        }

        $settingsArray = $this->mergeSettings($defaultSettings->toArray(), $settings->toArray());
        $settings->fromArray($settingsArray);

        return $settings;
    }

    /**
     * @param array $defaultSettingsArray
     * @param array $settingsArray
     *
     * @return array
     */
    protected function mergeSettings(array $defaultSettingsArray, array $settingsArray) {
        $settingsArray = array_filter($settingsArray, function($value) {
            return ($value !== null);
        });
        $settingsArray += $defaultSettingsArray;
        return $settingsArray;
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
