<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Communication\Plugin\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountRuleWithAttributesPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductDiscountConnector\Business\ProductDiscountConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscountConnector\ProductDiscountConnectorConfig getConfig()
 * @method \Spryker\Zed\ProductDiscountConnector\Persistence\ProductDiscountConnectorQueryContainerInterface getQueryContainer()
 */
class ProductAttributeCollectorPlugin extends AbstractPlugin implements CollectorPluginInterface, DiscountRuleWithAttributesPluginInterface
{
    /**
     * {@inheritDoc}
     * - Builds all product variants by abstract SKU.
     * - Looks for the attribute in any variants.
     * - Collects all matching items.
     * - Executes {@link \Spryker\Zed\ProductDiscountConnectorExtension\Dependency\Plugin\ProductAttributeCollectorExpanderPluginInterface} plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFacade()->collectByProductAttribute($quoteTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     * Name of field as used in query string
     *
     * @api
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'attribute';
    }

    /**
     * {@inheritDoc}
     * Data types used by this field. (string, integer, list)
     *
     * @api
     *
     * @return array<string>
     */
    public function acceptedDataTypes()
    {
        return [
            ComparatorOperators::TYPE_STRING,
            ComparatorOperators::TYPE_NUMBER,
            ComparatorOperators::TYPE_LIST,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getAttributeTypes()
    {
        return $this->getFacade()->getAttributeTypes();
    }
}
