<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Formatter;

use Generated\Shared\Transfer\MoneyTransfer;

class MoneyFormatter implements MoneyFormatterWithTypeInterface
{
    /**
     * @var \Spryker\Shared\Money\Formatter\MoneyFormatterCollectionInterface
     */
    protected $formatterCollection;

    /**
     * @param \Spryker\Shared\Money\Formatter\MoneyFormatterCollectionInterface $moneyFormatterCollection
     */
    public function __construct(MoneyFormatterCollectionInterface $moneyFormatterCollection)
    {
        $this->formatterCollection = $moneyFormatterCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     * @param string $type
     *
     * @return string
     */
    public function format(MoneyTransfer $moneyTransfer, $type)
    {
        $formatter = $this->formatterCollection->getFormatter($type);

        return $formatter->format($moneyTransfer);
    }
}
