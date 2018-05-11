<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency\CurrencyChange;

use Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface;
use Spryker\Yves\Currency\Dependency\Client\CurrencyToMessengerClientInterface;
use Spryker\Yves\Currency\Dependency\Client\CurrencyToZedRequestClientInterface;

class CurrencyPostChangePluginExecutor implements CurrencyPostChangePluginExecutorInterface
{
    /**
     * @var \Spryker\Yves\Currency\Dependency\CurrencyPostChangePluginInterface[]
     */
    protected $currencyPostChangePlugins = [];

    /**
     * @var \Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface
     */
    protected $currencyPersistence;

    /**
     * @var \Spryker\Yves\Currency\Dependency\Client\CurrencyToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @var \Spryker\Yves\Currency\Dependency\Client\CurrencyToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @param \Spryker\Yves\Currency\Dependency\CurrencyPostChangePluginInterface[] $currencyPostChangePlugins
     * @param \Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface $currencyPersistence
     * @param \Spryker\Yves\Currency\Dependency\Client\CurrencyToZedRequestClientInterface $zedRequestClient
     * @param \Spryker\Yves\Currency\Dependency\Client\CurrencyToMessengerClientInterface $messengerClient
     */
    public function __construct(
        array $currencyPostChangePlugins,
        CurrencyPersistenceInterface $currencyPersistence,
        CurrencyToZedRequestClientInterface $zedRequestClient,
        CurrencyToMessengerClientInterface $messengerClient
    ) {
        $this->currencyPostChangePlugins = $currencyPostChangePlugins;
        $this->currencyPersistence = $currencyPersistence;
        $this->zedRequestClient = $zedRequestClient;
        $this->messengerClient = $messengerClient;
    }

    /**
     * @param string $currencyIsoCode
     * @param string $previousCurrencyIsoCode
     *
     * @return void
     */
    public function execute($currencyIsoCode, $previousCurrencyIsoCode)
    {
        foreach ($this->currencyPostChangePlugins as $currencyPostChangePlugin) {
            if ($currencyPostChangePlugin->execute($currencyIsoCode)) {
                continue;
            }

            $this->currencyPersistence->setCurrentCurrencyIsoCode($previousCurrencyIsoCode);

            $this->addErrorMessages();

            return;
        }
    }

    /**
     * @return void
     */
    protected function addErrorMessages()
    {
        foreach ($this->zedRequestClient->getLastResponseErrorMessages() as $messageTransfer) {
            $this->messengerClient->addErrorMessage($messageTransfer->getValue());
        }
    }
}
