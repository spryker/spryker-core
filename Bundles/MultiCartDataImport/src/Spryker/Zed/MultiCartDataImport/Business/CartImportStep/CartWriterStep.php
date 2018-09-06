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
        $companyUnitAddressEntity = SpyQuoteQuery::create()
            ->filterByKey($dataSet[CartDataSetInterface::KEY_CART])
            ->findOneOrCreate();

        $companyUnitAddressEntity->fromArray($dataSet->getArrayCopy());
        $companyUnitAddressEntity
            ->setFkStore($dataSet[CartDataSetInterface::ID_STORE]);

        $companyUnitAddressEntity->save();
    }
}
