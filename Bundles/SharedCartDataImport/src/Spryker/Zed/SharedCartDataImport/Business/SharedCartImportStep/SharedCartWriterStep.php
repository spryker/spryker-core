<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SharedCartDataImport\Business\SharedCartImportStep;

use Orm\Zed\SharedCart\Persistence\SpyQuoteCompanyUserQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\SharedCartDataImport\Business\DataSet\SharedCartDataSetInterface;

class SharedCartWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $quoteCompanyUserEntity = SpyQuoteCompanyUserQuery::create()
            ->filterByFkQuote($dataSet[SharedCartDataSetInterface::ID_QUOTE])
            ->filterByFkCompanyUser($dataSet[SharedCartDataSetInterface::ID_COMPANY_USER])
            ->findOneOrCreate();

        $quoteCompanyUserEntity->setFkQuotePermissionGroup($dataSet[SharedCartDataSetInterface::ID_PERMISSION_GROUP]);

        $quoteCompanyUserEntity->save();
    }
}
