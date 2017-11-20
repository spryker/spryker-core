<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SequenceNumber\Business;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGenerator;
use Spryker\Zed\SequenceNumber\Business\Model\SequenceNumber;

/**
 * @method \Spryker\Zed\SequenceNumber\SequenceNumberConfig getConfig()
 * @method \Spryker\Zed\SequenceNumber\Persistence\SequenceNumberQueryContainerInterface getQueryContainer()
 */
class SequenceNumberBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @param int $min
     * @param int $max
     *
     * @return \Spryker\Zed\SequenceNumber\Business\Generator\RandomNumberGeneratorInterface
     */
    public function createRandomNumberGenerator($min = 1, $max = 1)
    {
        return new RandomNumberGenerator(
            $min,
            $max
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SequenceNumberSettingsTransfer $sequenceNumberSettings
     *
     * @return \Spryker\Zed\SequenceNumber\Business\Model\SequenceNumberInterface
     */
    public function createSequenceNumber(SequenceNumberSettingsTransfer $sequenceNumberSettings)
    {
        $settings = $this->getConfig()->getDefaultSettings($sequenceNumberSettings);

        $generator = $this->createRandomNumberGenerator($settings->getIncrementMinimum(), $settings->getIncrementMaximum());

        return new SequenceNumber(
            $generator,
            $settings,
            Propel::getConnection(),
            $this->getConfig()->getSequenceLimits()
        );
    }
}
