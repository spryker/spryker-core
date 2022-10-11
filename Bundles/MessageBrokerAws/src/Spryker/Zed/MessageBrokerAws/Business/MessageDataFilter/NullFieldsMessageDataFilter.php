<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\MessageDataFilter;

use Generated\Shared\Transfer\MessageDataFilterConfigurationTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class NullFieldsMessageDataFilter implements MessageDataFilterInterface
{
    /**
     * @var \Spryker\Zed\MessageBrokerAws\Business\MessageDataFilter\MessageDataFilterConfigurator
     */
    protected MessageDataFilterConfigurator $configurator;

    /**
     * @param \Spryker\Zed\MessageBrokerAws\Business\MessageDataFilter\MessageDataFilterConfigurator $configurator
     */
    public function __construct(MessageDataFilterConfigurator $configurator)
    {
        $this->configurator = $configurator;
    }

    /**
     * @inheritDoc
     */
    public function filter(array $messageData, AbstractTransfer $messageTransfer): array
    {
        $configuration = $this->configurator->getItemFromTransferOrDefault(
            $messageTransfer,
            MessageDataFilterConfigurationTransfer::STRIP_NULL_FIELDS_CONFIGURATION,
        );

        if ($configuration->getDisabled()) {
            return $messageData;
        }

        return $this->filterNullValues($messageData);
    }

    /**
     * @param array<string, mixed> $messageData
     *
     * @return array<string, mixed>
     */
    protected function filterNullValues(array $messageData): array
    {
        foreach ($messageData as $key => $value) {
            if (is_array($value)) {
                $messageData[$key] = $this->filterNullValues($value);

                continue;
            }

            if ($value === null) {
                unset($messageData[$key]);
            }
        }

        return $messageData;
    }
}
