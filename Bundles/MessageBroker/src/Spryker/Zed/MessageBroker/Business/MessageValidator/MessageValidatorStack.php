<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\MessageValidator;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

class MessageValidatorStack implements MessageValidatorStackInterface
{
    /**
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageValidatorPluginInterface>
     */
    protected array $validatorPlugins;

    /**
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageValidatorPluginInterface> $internalValidatorPlugins
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageValidatorPluginInterface> $externalValidatorPlugins
     */
    public function __construct(array $internalValidatorPlugins, array $externalValidatorPlugins)
    {
        $this->validatorPlugins = array_merge($internalValidatorPlugins, $externalValidatorPlugins);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return bool
     */
    public function isValidMessage(TransferInterface $messageTransfer): bool
    {
        $isValidMessage = true;
        foreach ($this->validatorPlugins as $messageValidator) {
            $isValidMessage = $messageValidator->isValid($messageTransfer);

            if (!$isValidMessage) {
                $isValidMessage = false;
            }
        }

        return $isValidMessage;
    }
}
