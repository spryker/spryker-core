<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Money;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;

/**
 * @method \Spryker\Client\Money\MoneyFactory getFactory()
 */
class MoneyClient extends AbstractClient implements MoneyClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithSymbol(MoneyTransfer $moneyTransfer): string
    {
        return $this->getFactory()
            ->createMoneyFormatter()
            ->format($moneyTransfer, MoneyFormatterCollection::FORMATTER_WITH_SYMBOL);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $amount
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromInteger(int $amount, ?string $isoCode): MoneyTransfer
    {
        return $this->getFactory()
            ->createMoneyBuilder()
            ->fromInteger($amount, $isoCode);
    }
}
