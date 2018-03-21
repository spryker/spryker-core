<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyDataImport\Business\Model;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyWriterStep implements DataImportStepInterface
{
    const KEY_NAME = 'name';
    const KEY_IS_ACTIVE = 'is_active';
    const KEY_STATUS = 'status';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $currencyEntity = SpyCompanyQuery::create()
            ->filterByName($dataSet[static::KEY_NAME])
            ->findOneOrCreate();

        $currencyEntity->setIsActive($dataSet[static::KEY_IS_ACTIVE]);
        $currencyEntity->setStatus($dataSet[static::KEY_STATUS]);

        $currencyEntity->save();
    }
}
