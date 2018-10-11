<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Discount\Communication\Plugin\Collector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class ItemBySkuCollectorPlugin extends AbstractPlugin implements CollectorPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFacade()->collectBySku($quoteTransfer, $clauseTransfer);
    }

    /**
     * Name of field as used in query string
     *
     * @api
     *
     * @return string
     */
    public function getFieldName()
    {
        return 'sku';
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
            ComparatorOperators::TYPE_LIST,
        ];
    }
}
