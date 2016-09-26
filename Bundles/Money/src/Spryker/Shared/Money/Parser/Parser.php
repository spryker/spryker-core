<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Parser;

use Generated\Shared\Transfer\CurrencyTransfer;
use Money\MoneyParser;
use Spryker\Shared\Money\Mapper\MoneyToTransferMapperInterface;

class Parser implements ParserInterface
{

    /**
     * @var \Money\Parser\IntlMoneyParser
     */
    protected $moneyParser;

    /**
     * @var \Spryker\Shared\Money\Mapper\MoneyToTransferMapperInterface
     */
    protected $moneyToTransferMapper;

    /**
     * @param \Money\MoneyParser $moneyParser
     * @param \Spryker\Shared\Money\Mapper\MoneyToTransferMapperInterface $moneyToTransferMapper
     */
    public function __construct(MoneyParser $moneyParser, MoneyToTransferMapperInterface $moneyToTransferMapper)
    {
        $this->moneyParser = $moneyParser;
        $this->moneyToTransferMapper = $moneyToTransferMapper;
    }

    /**
     * @param string $value
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function parse($value, CurrencyTransfer $currencyTransfer)
    {
        $money = $this->moneyParser->parse($value, $currencyTransfer->getCode());

        return $this->moneyToTransferMapper->convert($money);
    }

}
