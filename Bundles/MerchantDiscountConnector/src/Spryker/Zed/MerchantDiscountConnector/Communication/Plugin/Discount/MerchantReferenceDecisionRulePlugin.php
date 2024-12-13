<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantDiscountConnector\Communication\Plugin\Discount;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountRuleWithValueOptionsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantDiscountConnector\MerchantDiscountConnectorConfig getConfig()
 * @method \Spryker\Zed\MerchantDiscountConnector\Business\MerchantDiscountConnectorFacadeInterface getFacade()
 */
class MerchantReferenceDecisionRulePlugin extends AbstractPlugin implements DecisionRulePluginInterface, DiscountRuleWithValueOptionsPluginInterface
{
    /**
     * @var string
     */
    protected const FIELD_NAME_MERCHANT_REFERENCE = 'merchant-reference';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::TYPE_LIST
     *
     * @var string
     */
    protected const TYPE_LIST = 'list';

    /**
     * {@inheritDoc}
     * - Expects `ItemTransfer.merchantReference` to be set.
     * - Checks if `ItemTransfer.merchantReference` matches clause.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer): bool
    {
        return $this->getFacade()->isMerchantReferenceSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getFieldName(): string
    {
        return static::FIELD_NAME_MERCHANT_REFERENCE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return list<string>
     */
    public function acceptedDataTypes(): array
    {
        return [
            static::TYPE_LIST,
        ];
    }

    /**
     * {@inheritDoc}
     * - Reads the collection of merchants from Persistence.
     * - Returns associative array [merchant reference => merchant name].
     *
     * @api
     *
     * @return array<int|string, string>
     */
    public function getQueryStringValueOptions(): array
    {
        return $this->getFacade()->getMerchantNamesIndexedByMerchantReference();
    }
}
