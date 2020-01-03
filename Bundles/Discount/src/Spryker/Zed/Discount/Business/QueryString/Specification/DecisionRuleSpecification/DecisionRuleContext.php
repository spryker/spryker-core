<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface;

class DecisionRuleContext implements DecisionRuleSpecificationInterface
{
     /**
      * @var \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface
      */
    protected $rulePlugin;

    /**
     * @var \Generated\Shared\Transfer\ClauseTransfer
     */
    protected $clauseTransfer;

     /**
      * @param \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface $rulePlugin
      * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
      */
    public function __construct(DecisionRulePluginInterface $rulePlugin, ClauseTransfer $clauseTransfer)
    {
        $this->rulePlugin = $rulePlugin;
        $this->clauseTransfer = $clauseTransfer;
    }

     /**
      * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
      * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
      *
      * @return bool
      */
    public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer)
    {
        $this->setAcceptedDataTypes();

        return $this->rulePlugin->isSatisfiedBy($quoteTransfer, $itemTransfer, $this->clauseTransfer);
    }

     /**
      * @return void
      */
    protected function setAcceptedDataTypes()
    {
        $this->clauseTransfer->setAcceptedTypes($this->rulePlugin->acceptedDataTypes());
    }
}
