<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantDiscountConnector\Communication\Plugin\Discount;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemCollectorPluginInterface;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountRuleWithValueOptionsPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantDiscountConnector\MerchantDiscountConnectorConfig getConfig()
 * @method \Spryker\Zed\MerchantDiscountConnector\Business\MerchantDiscountConnectorFacadeInterface getFacade()
 */
class MerchantReferenceDiscountableItemCollectorPlugin extends AbstractPlugin implements DiscountableItemCollectorPluginInterface, DiscountRuleWithValueOptionsPluginInterface
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
     * - Requires `QuoteTransfer.priceMode` to be set.
     * - Requires either `QuoteTransfer.item.unitNetPrice` or `QuoteTransfer.item.unitGrossPrice` to be set depending on price mode.
     * - Collects discountable items from the given quote by item merchant reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): array
    {
        return $this->getFacade()->collectDiscountableItemsByMerchantReference($quoteTransfer, $clauseTransfer);
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
