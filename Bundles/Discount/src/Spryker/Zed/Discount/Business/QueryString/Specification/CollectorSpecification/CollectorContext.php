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
     * @var CollectorPluginInterface
     */
    protected $collectorPlugin;

    /**
     * @var ClauseTransfer
     */
    protected $clauseTransfer;

    /**
     * @param CollectorPluginInterface $collectorPlugin
     * @param ClauseTransfer $clauseTransfer
     */
    public function __construct(CollectorPluginInterface $collectorPlugin, ClauseTransfer $clauseTransfer)
    {
        $this->collectorPlugin = $collectorPlugin;
        $this->clauseTransfer = $clauseTransfer;
    }


    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function collect(QuoteTransfer $quoteTransfer)
    {
        $this->setAcceptedDataTypes();
        return $this->collectorPlugin->collect($quoteTransfer, $this->clauseTransfer);
    }

    /**
     * @return $this
     */
    protected function setAcceptedDataTypes()
    {
        return $this->clauseTransfer->setAcceptedTypes($this->collectorPlugin->acceptedDataTypes());
    }
}
