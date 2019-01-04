<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountRuleWithValueOptionsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 * @method \Spryker\Zed\Discount\DiscountConfig getConfig()
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 */
class PriceModeDecisionRulePlugin extends AbstractPlugin implements DecisionRulePluginInterface, DiscountRuleWithValueOptionsPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {

        return $this->getFacade()
            ->isPriceModeDecisionRuleSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'price-mode';
    }

    /**
     * @api
     *
     * @return array
     */
    public function acceptedDataTypes()
    {
        return [
            ComparatorOperators::TYPE_STRING,
        ];
    }

    /**
     * @api
     *
     * @return array
     */
    public function getQueryStringValueOptions()
    {
        return [
          'GROSS_MODE' => 'Gross mode',
          'NET_MODE' => 'Net Mode',
        ];
    }
}
