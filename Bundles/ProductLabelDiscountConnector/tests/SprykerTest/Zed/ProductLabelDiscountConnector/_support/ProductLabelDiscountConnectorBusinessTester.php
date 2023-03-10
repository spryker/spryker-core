<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelDiscountConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ProductLabelDiscountConnector\Business\ProductLabelDiscountConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductLabelDiscountConnectorBusinessTester extends Actor
{
    use _generated\ProductLabelDiscountConnectorBusinessTesterActions;

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(array $productConcreteTransfers): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $itemTransfer = new ItemTransfer();
            $itemTransfer
                ->setId($productConcreteTransfer->getIdProductConcrete())
                ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());

            $quoteTransfer->addItem($itemTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param list<string> $acceptedTypes
     * @param string $value
     * @param string $operator
     *
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    public function createClauseTransfer(
        array $acceptedTypes,
        string $value,
        string $operator
    ): ClauseTransfer {
        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setOperator($operator);
        $clauseTransfer->setField('label');
        $clauseTransfer->setValue($value);
        $clauseTransfer->setAcceptedTypes($acceptedTypes);

        return $clauseTransfer;
    }

    /**
     * @param array<array<string, mixed>> $productLabelsData
     * @param array<string, mixed> $productConcreteOverride
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProductWithProductLabels(array $productLabelsData, array $productConcreteOverride = []): ProductConcreteTransfer
    {
        $productConcreteTransfer = $this->haveProduct($productConcreteOverride);
        foreach ($productLabelsData as $productLabelData) {
            $productLabelTransfer = $this->haveProductLabel($productLabelData);

            $this->haveProductLabelToAbstractProductRelation(
                $productLabelTransfer->getIdProductLabel(),
                $productConcreteTransfer->getFkProductAbstract(),
            );
        }

        return $productConcreteTransfer;
    }
}
