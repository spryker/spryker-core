<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Money\Plugin\Twig;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Money\Exception\WrongMoneyValueTypeException;
use Twig\Environment;
use Twig\TwigFilter;

/**
 * @method \Spryker\Yves\Money\MoneyFactory getFactory()
 */
class MoneyTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const FILTER_NAME_MONEY = 'money';
    protected const FILTER_NAME_MONEY_RAW = 'moneyRaw';

    protected const WRONG_MONEY_TYPE_ERROR_MESSAGE = 'Argument 1 passed to %s::getMoneyTransfer() must be of the type integer, string or float, %s given.';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig = $this->addTwigFilters($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigFilters(Environment $twig): Environment
    {
        $twig->addFilter($this->getMoneyFilter());
        $twig->addFilter($this->getMoneyRawFilter());

        return $twig;
    }

    /**
     * @return \Twig\TwigFilter
     */
    protected function getMoneyFilter(): TwigFilter
    {
        $moneyFactory = $this->getFactory();

        $filter = new TwigFilter(static::FILTER_NAME_MONEY, function ($money, bool $withSymbol = true, ?string $isoCode = null) use ($moneyFactory) {
            if (!$money instanceof MoneyTransfer) {
                $money = $this->getMoneyTransfer($money, $isoCode);
            }

            $formatterType = MoneyFormatterCollection::FORMATTER_WITHOUT_SYMBOL;
            if ($withSymbol) {
                $formatterType = MoneyFormatterCollection::FORMATTER_WITH_SYMBOL;
            }

            return $moneyFactory->createMoneyFormatter()->format($money, $formatterType);
        });

        return $filter;
    }

    /**
     * @return \Twig\TwigFilter
     */
    protected function getMoneyRawFilter(): TwigFilter
    {
        $moneyFactory = $this->getFactory();

        $filter = new TwigFilter(static::FILTER_NAME_MONEY_RAW, function ($money, $isoCode = null) use ($moneyFactory) {
            if (!$money instanceof MoneyTransfer) {
                $money = $this->getMoneyTransfer($money, $isoCode);
            }

            return $moneyFactory->createIntegerToDecimalConverter()->convert((int)$money->getAmount());
        });

        return $filter;
    }

    /**
     * @param mixed $money
     * @param string|null $isoCode
     *
     * @throws \Spryker\Yves\Money\Exception\WrongMoneyValueTypeException
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function getMoneyTransfer($money, ?string $isoCode = null): MoneyTransfer
    {
        $moneyBuilder = $this->getFactory()->createMoneyBuilder();

        if (is_int($money)) {
            return $moneyBuilder->fromInteger($money, $isoCode);
        }

        if (is_string($money)) {
            return $moneyBuilder->fromString($money, $isoCode);
        }

        if (is_float($money)) {
            return $moneyBuilder->fromFloat($money, $isoCode);
        }

        throw new WrongMoneyValueTypeException(sprintf(static::WRONG_MONEY_TYPE_ERROR_MESSAGE, static::class, gettype($money)));
    }
}
