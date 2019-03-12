<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Plugin\Twig;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Money\Business\MoneyFacadeInterface;
use Spryker\Zed\Money\Communication\Exception\WrongMoneyValueTypeException;
use Twig\Environment;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\Money\MoneyConfig getConfig()
 * @method \Spryker\Zed\Money\Communication\MoneyCommunicationFactory getFactory()
 * @method \Spryker\Zed\Money\Business\MoneyFacadeInterface getFacade()
 */
class MoneyTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const FUNCTION_NAME_MONEY_COLLECTION = 'form_money_collection';
    protected const FILTER_NAME_MONEY = 'money';

    protected const WRONG_MONEY_TYPE_ERROR_MESSAGE = 'Argument 1 passed to %s::getMoneyTransfer() must be of the type integer, string or float, %s given.';

    /**
     * {@inheritdoc}
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
        $twig = $this->addTwigFunctions($twig);

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

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigFunctions(Environment $twig): Environment
    {
        $twig->addFunction($this->getMoneyFormTableFunction($twig));

        return $twig;
    }

    /**
     * @return \Twig\TwigFilter
     */
    protected function getMoneyFilter(): TwigFilter
    {
        $moneyFacade = $this->getFacade();

        $filter = new TwigFilter(static::FILTER_NAME_MONEY, function ($money, bool $withSymbol = true, ?string $isoCode = null) use ($moneyFacade) {
            if (!$money instanceof MoneyTransfer) {
                $money = $this->getMoneyTransferByValueType($money, $moneyFacade, $isoCode);
            }

            if ($withSymbol) {
                return $moneyFacade->formatWithSymbol($money);
            }

            return $moneyFacade->formatWithoutSymbol($money);
        });

        return $filter;
    }

    /**
     * @param mixed $money
     * @param \Spryker\Zed\Money\Business\MoneyFacadeInterface $moneyFacade
     * @param string|null $isoCode
     *
     * @throws \Spryker\Zed\Money\Communication\Exception\WrongMoneyValueTypeException
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function getMoneyTransferByValueType($money, MoneyFacadeInterface $moneyFacade, ?string $isoCode = null): MoneyTransfer
    {
        if (is_int($money)) {
            return $moneyFacade->fromInteger($money, $isoCode);
        }

        if (is_string($money)) {
            return $moneyFacade->fromString($money, $isoCode);
        }

        if (is_float($money)) {
            return $moneyFacade->fromFloat($money, $isoCode);
        }

        throw new WrongMoneyValueTypeException(sprintf(static::WRONG_MONEY_TYPE_ERROR_MESSAGE, static::class, gettype($money)));
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFunction
     */
    protected function getMoneyFormTableFunction(Environment $twig): TwigFunction
    {
        $options = ['is_safe' => ['html']];

        return new TwigFunction(
            static::FUNCTION_NAME_MONEY_COLLECTION,
            function ($moneyValueFormViewCollection) use ($twig) {
                return $twig->render(
                    $this->getConfig()->getMoneyTableTemplatePath(),
                    [
                        'moneyValueFormViewCollection' => $moneyValueFormViewCollection,
                    ]
                );
            },
            $options
        );
    }
}
