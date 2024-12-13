<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferDiscountConnector\Communication\Plugin\Discount;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\DiscountableItemCollectorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOfferDiscountConnector\Business\ProductOfferDiscountConnectorFacadeInterface getFacade()
 */
class ProductOfferReferenceDiscountableItemCollectorPlugin extends AbstractPlugin implements DiscountableItemCollectorPluginInterface
{
    /**
     * @uses \Spryker\Zed\ProductOfferDiscountConnector\Communication\Plugin\Discount\ProductOfferReferenceDecisionRulePlugin::FIELD_NAME_PRODUCT_OFFER_REFERENCE
     *
     * @var string
     */
    protected const FIELD_NAME_PRODUCT_OFFER_REFERENCE = 'product-offer-reference';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators::TYPE_STRING
     *
     * @var string
     */
    protected const TYPE_STRING = 'string';

    /**
     * {@inheritDoc}
     * - Requires `QuoteTransfer.priceMode` to be set.
     * - Requires one of either `QuoteTransfer.item.unitNetPrice` or `QuoteTransfer.item.unitGrossPrice` to be set depending on price mode.
     * - Collects discountable items from the given quote by items' product offer references.
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
        return $this->getFacade()->getDiscountableItemsByProductOfferReference($quoteTransfer, $clauseTransfer);
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
        return static::FIELD_NAME_PRODUCT_OFFER_REFERENCE;
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
            static::TYPE_STRING,
        ];
    }
}
