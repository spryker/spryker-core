<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\MessageDataFilter;

use Generated\Shared\Transfer\MessageDataFilterConfigurationTransfer;
use Generated\Shared\Transfer\MessageDataFilterItemConfigurationTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class IdFieldsMessageDataFilter implements MessageDataFilterInterface
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
            MessageDataFilterConfigurationTransfer::STRIP_ID_FIELDS_CONFIGURATION,
        );

        if ($configuration->getDisabled()) {
            return $messageData;
        }

        return $this->stripIds($messageData, $configuration);
    }

    /**
     * @param array<string, mixed> $messageData
     * @param \Generated\Shared\Transfer\MessageDataFilterItemConfigurationTransfer $stripIdFieldsConfiguration
     *
     * @return array<string, mixed>
     */
    protected function stripIds(
        array $messageData,
        MessageDataFilterItemConfigurationTransfer $stripIdFieldsConfiguration
    ): array {
        foreach ($messageData as $key => $value) {
            if ($this->isIdField($key, $stripIdFieldsConfiguration->getPatterns())) {
                unset($messageData[$key]);

                continue;
            }

            if (is_array($value)) {
                $messageData[$key] = $this->stripIds($value, $stripIdFieldsConfiguration);
            }
        }

        return $messageData;
    }

    /**
     * @param string $name
     * @param array<string> $idPatterns
     *
     * @return bool
     */
    protected function isIdField(string $name, array $idPatterns): bool
    {
        foreach ($idPatterns as $regex) {
            if (preg_match($regex, $name)) {
                return true;
            }
        }

        return false;
    }
}
