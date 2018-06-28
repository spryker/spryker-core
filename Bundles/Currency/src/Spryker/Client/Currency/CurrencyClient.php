<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Currency\CurrencyFactory getFactory()
 */
class CurrencyClient extends AbstractClient implements CurrencyClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function fromIsoCode($isoCode)
    {
        return $this->getFactory()->createCurrencyBuilder()->fromIsoCode($isoCode);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent()
    {
        return $this->getFactory()->createCurrencyBuilder()->getCurrent();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function setCurrentCurrencyIsoCode(string $currencyIsoCode): void
    {
        $this->getFactory()
            ->createCurrencyUpdater()
            ->setCurrentCurrencyIsoCode($currencyIsoCode);
    }
}
