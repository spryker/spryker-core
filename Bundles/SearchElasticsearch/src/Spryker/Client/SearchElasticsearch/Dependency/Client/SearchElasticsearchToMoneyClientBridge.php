<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Dependency\Client;

use Generated\Shared\Transfer\MoneyTransfer;

class SearchElasticsearchToMoneyClientBridge implements SearchElasticsearchToMoneyClientInterface
{
    /**
     * @var \Spryker\Client\Money\MoneyClientInterface
     */
    protected $moneyClient;

    /**
     * @param \Spryker\Client\Money\MoneyClientInterface $moneyClient
     */
    public function __construct($moneyClient)
    {
        $this->moneyClient = $moneyClient;
    }

    /**
     * @param float $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromFloat(float $amount, ?string $isoCode = null): MoneyTransfer
    {
        return $this->moneyClient->fromFloat($amount, $isoCode);
    }
}
