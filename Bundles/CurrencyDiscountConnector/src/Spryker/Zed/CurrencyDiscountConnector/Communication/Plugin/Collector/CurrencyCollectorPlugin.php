<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyDiscountConnector\Communication\Plugin\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountRuleWithValueOptionsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CurrencyDiscountConnector\Business\CurrencyDiscountConnectorFacade getFacade()
 * @method \Spryker\Zed\CurrencyDiscountConnector\Communication\CurrencyDiscountConnectorCommunicationFactory getFactory()
 */
class CurrencyCollectorPlugin extends AbstractPlugin implements CollectorPluginInterface, DiscountRuleWithValueOptionsPluginInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFacade()->collectDiscountableItemsFor($quoteTransfer, $clauseTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'item-currency';
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[]
     */
    public function acceptedDataTypes()
    {
        return [
            ComparatorOperators::TYPE_STRING
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getQueryStringValueOptions()
    {
        $storeCurrencies = $this->getFactory()->getCurrencyFacade()->getStoreCurrencies();

        $currencies = [];
        foreach ($storeCurrencies as $currencyTransfer) {
            $currencies[$currencyTransfer->getCode()] = $currencyTransfer->getName();
        }

        return $currencies;
    }

}
