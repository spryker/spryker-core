<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDiscountConnector\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToDiscountInterface;
use Spryker\Zed\ProductLabelDiscountConnector\Dependency\QueryContainer\ProductLabelDiscountConnectorToProductLabelInterface;

class ProductLabelDecisionRule implements ProductLabelDecisionRuleInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @var \Spryker\Zed\ProductLabelDiscountConnector\Dependency\QueryContainer\ProductLabelDiscountConnectorToProductLabelInterface
     */
    protected $productLabelQueryContainer;

    /**
     * @param \Spryker\Zed\ProductLabelDiscountConnector\Dependency\Facade\ProductLabelDiscountConnectorToDiscountInterface $discountFacade
     * @param \Spryker\Zed\ProductLabelDiscountConnector\Dependency\QueryContainer\ProductLabelDiscountConnectorToProductLabelInterface $productLabelQueryContainer
     */
    public function __construct(
        ProductLabelDiscountConnectorToDiscountInterface $discountFacade,
        ProductLabelDiscountConnectorToProductLabelInterface $productLabelQueryContainer
    ) {
        $this->discountFacade = $discountFacade;
        $this->productLabelQueryContainer = $productLabelQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $currentItemTransfer, ClauseTransfer $clauseTransfer)
    {
        $currentItemTransfer->requireIdProductAbstract();
        $idProductAbstract = $currentItemTransfer->getIdProductAbstract();

        $productLabelEntities = $this->findValidProductLabelEntities($idProductAbstract);
        foreach ($productLabelEntities as $productLabelEntity) {
            if ($this->discountFacade->queryStringCompare($clauseTransfer, $productLabelEntity->getName())) {
                return true;
            }

            if ($productLabelEntity->isExclusive()) {
                break;
            }
        }

        return false;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findValidProductLabelEntities($idProductAbstract)
    {
        $productLabelEntities = $this->productLabelQueryContainer
            ->queryValidProductLabelsByIdProductAbstract($idProductAbstract)
            ->find();

        return $productLabelEntities;
    }
}
