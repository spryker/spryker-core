<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface;

class CollectorContext implements CollectorSpecificationInterface
{
    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface
     */
    protected $collectorPlugin;

    /**
     * @var \Generated\Shared\Transfer\ClauseTransfer
     */
    protected $clauseTransfer;

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface $collectorPlugin
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     */
    public function __construct(CollectorPluginInterface $collectorPlugin, ClauseTransfer $clauseTransfer)
    {
        $this->collectorPlugin = $collectorPlugin;
        $this->clauseTransfer = $clauseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer)
    {
        $this->setAcceptedDataTypes();

        return $this->collectorPlugin->collect($quoteTransfer, $this->clauseTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    protected function setAcceptedDataTypes()
    {
        return $this->clauseTransfer->setAcceptedTypes($this->collectorPlugin->acceptedDataTypes());
    }
}
