<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency\CurrencyChange;

use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Client\Currency\Dependency\Client\CurrencyToZedRequestClientInterface;

class CurrencyPostChangePluginExecutor implements CurrencyPostChangePluginExecutorInterface
{
    /**
     * @var \Spryker\Client\CurrencyExtension\Dependency\CurrencyPostChangePluginInterface[]
     */
    protected $currencyPostChangePlugins = [];

    /**
     * @var \Spryker\Client\Currency\Dependency\Client\CurrencyToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\Currency\Dependency\Client\CurrencyToZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Client\CurrencyExtension\Dependency\CurrencyPostChangePluginInterface[] $currencyPostChangePlugins
     */
    public function __construct(
        CurrencyToZedRequestClientInterface $zedRequestClient,
        array $currencyPostChangePlugins
    ) {
        $this->currencyPostChangePlugins = $currencyPostChangePlugins;
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currency
     *
     * @return bool
     */
    public function execute(CurrencyTransfer $currency): bool
    {
        foreach ($this->currencyPostChangePlugins as $currencyPostChangePlugins) {
            if (!$currencyPostChangePlugins->execute($currency)) {
                $this->zedRequestClient->addResponseMessagesToMessenger();

                return false;
            }
        }
        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();

        return true;
    }
}
