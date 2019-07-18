<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MultiCartDataImport\Business\CartImportStep;

use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MultiCartDataImport\Business\DataSet\CartDataSetInterface;

class CartWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $quoteEntity = SpyQuoteQuery::create()
            ->filterByKey($dataSet[CartDataSetInterface::KEY_CART])
            ->findOneOrCreate();

        $quoteEntity->fromArray($dataSet->getArrayCopy());
        $quoteEntity
            ->setFkStore($dataSet[CartDataSetInterface::ID_STORE]);

        $quoteEntity->save();
    }
}
