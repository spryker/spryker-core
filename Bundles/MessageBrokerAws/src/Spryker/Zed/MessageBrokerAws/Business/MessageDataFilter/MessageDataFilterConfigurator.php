<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\MessageDataFilter;

use Generated\Shared\Transfer\MessageDataFilterConfigurationTransfer;
use Generated\Shared\Transfer\MessageDataFilterItemConfigurationTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class MessageDataFilterConfigurator
{
    /**
     * @var \Generated\Shared\Transfer\MessageDataFilterConfigurationTransfer
     */
    protected MessageDataFilterConfigurationTransfer $defaultFilterConfiguration;

    /**
     * @param \Generated\Shared\Transfer\MessageDataFilterConfigurationTransfer $defaultFilterConfiguration
     */
    public function __construct(MessageDataFilterConfigurationTransfer $defaultFilterConfiguration)
    {
        $this->defaultFilterConfiguration = $defaultFilterConfiguration;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $messageTransfer
     * @param string $itemKey A valid property key from \Generated\Shared\Transfer\MessageDataFilterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\MessageDataFilterItemConfigurationTransfer
     */
    public function getItemFromTransferOrDefault(
        AbstractTransfer $messageTransfer,
        string $itemKey
    ): MessageDataFilterItemConfigurationTransfer {
        $itemConfiguration = null;
        if (
            $messageTransfer->offsetExists('dataFilterConfiguration')
            && $messageTransfer->offsetGet('dataFilterConfiguration')
        ) {
            /** @var \Generated\Shared\Transfer\MessageDataFilterConfigurationTransfer $configuration */
            $configuration = $messageTransfer->offsetGet('dataFilterConfiguration');
            $itemConfiguration = $configuration->offsetGet($itemKey);
        }

        if (!$itemConfiguration) {
            $itemConfiguration = $this->defaultFilterConfiguration->offsetGet($itemKey);
        }

        return $itemConfiguration;
    }
}
