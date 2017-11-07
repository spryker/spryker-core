<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency\CurrencyChange;

use Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface;
use Spryker\Yves\Currency\Dependency\Client\CurrencyToZedRequestClientInterface;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessenger;
use Spryker\Yves\Kernel\Plugin\Pimple;

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
     * @param \Spryker\Yves\Currency\Dependency\CurrencyPostChangePluginInterface[] $currencyPostChangePlugins
     * @param \Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface $currencyPersistence
     * @param \Spryker\Yves\Currency\Dependency\Client\CurrencyToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(
        array $currencyPostChangePlugins,
        CurrencyPersistenceInterface $currencyPersistence,
        CurrencyToZedRequestClientInterface $zedRequestClient
    )
    {
        $this->currencyPostChangePlugins = $currencyPostChangePlugins;
        $this->currencyPersistence = $currencyPersistence;
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param string $currencyIsoCode
     * @param string $previousCurrencyIsoCode
     *
     * @return void
     */
    public function execute($currencyIsoCode, $previousCurrencyIsoCode)
    {
        foreach ($this->currencyPostChangePlugins as $currencyPostChangePlugins) {
            if ($currencyPostChangePlugins->execute($currencyIsoCode)) {
                continue;
            }
            $this->currencyPersistence->setCurrentCurrencyIsoCode($previousCurrencyIsoCode);

            $pimplePlugin = new Pimple();
            $flashMessenger = $pimplePlugin->getApplication()['flash_messenger'];

            foreach ($this->zedRequestClient->getLastResponseErrorMessages() as $errorMessage) {
                $flashMessenger->addErrorMessage($errorMessage->getValue());
            }

            return;
        }
    }
}
