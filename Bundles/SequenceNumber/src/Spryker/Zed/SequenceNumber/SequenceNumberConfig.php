<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SequenceNumber;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Shared\SequenceNumber\SequenceNumberConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SequenceNumberConfig extends AbstractBundleConfig
{
    /**
     * @param \Generated\Shared\Transfer\SequenceNumberSettingsTransfer|null $settings
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getDefaultSettings(?SequenceNumberSettingsTransfer $settings = null)
    {
        $defaultSettings = new SequenceNumberSettingsTransfer();
        $defaultSettings->setName($this->getSequenceName());
        $defaultSettings->setIncrementMinimum($this->getNumberIncrementMin());
        $defaultSettings->setIncrementMaximum($this->getNumberIncrementMax());
        $defaultSettings->setOffset($this->getOffset());

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
    protected function mergeSettings(array $defaultSettingsArray, array $settingsArray)
    {
        $settingsArray = array_filter($settingsArray, function ($value) {
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
    public function getOffset()
    {
        return 0;
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

    /**
     * @return string
     */
    public function getSequenceName()
    {
        return 'Sequence';
    }

    /**
     * @return array
     */
    public function getSequenceLimits()
    {
        return $this->getConfig()->get(SequenceNumberConstants::LIMIT_LIST, []);
    }
}
