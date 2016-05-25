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
      * @var DecisionRulePluginInterface
      */
     protected $rulePlugin;

    /**
     * @var ClauseTransfer
     */
     protected $clauseTransfer;

     /**
      * @param DecisionRulePluginInterface $rulePlugin
      * @param ClauseTransfer $clauseTransfer
      */
     public function __construct(DecisionRulePluginInterface $rulePlugin, ClauseTransfer $clauseTransfer)
     {
          $this->rulePlugin = $rulePlugin;
          $this->clauseTransfer = $clauseTransfer;
     }

     /**
      *
      * @param QuoteTransfer $quoteTransfer
      * @param ItemTransfer $itemTransfer
      *
      * @return bool
      */
     public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer)
     {
          $this->setAcceptedDataTypes();
          return $this->rulePlugin->isSatisfiedBy($quoteTransfer, $itemTransfer, $this->clauseTransfer);
     }

     /**
      * @return $this
      */
     protected function setAcceptedDataTypes()
     {
          return $this->clauseTransfer->setAcceptedTypes($this->rulePlugin->acceptedDataTypes());
     }
}
