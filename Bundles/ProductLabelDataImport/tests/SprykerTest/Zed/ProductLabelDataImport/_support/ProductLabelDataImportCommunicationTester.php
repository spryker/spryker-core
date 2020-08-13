<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductLabelDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductLabelDataImportCommunicationTester extends Actor
{
    use _generated\ProductLabelDataImportCommunicationTesterActions;

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function removeProductLabelProductAbstractRelationsByProductAbstractTransfer(
        ProductAbstractTransfer $productAbstractTransfer
    ): void {
        $this->createProductLabelProductAbstractQuery()
            ->filterByFkProductAbstract($productAbstractTransfer->getIdProductAbstract())
            ->delete();
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    protected function createProductLabelProductAbstractQuery(): SpyProductLabelProductAbstractQuery
    {
        return SpyProductLabelProductAbstractQuery::create();
    }
}
