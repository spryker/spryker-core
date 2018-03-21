<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\CompanyBusinessUnitDataImport\Exception\CompanyNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyBusinessUnitWriterStep implements DataImportStepInterface
{
    const KEY_NAME = 'name';

    /**
     * @var int
     */
    protected $idCompany;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $idCompany = $this->getIdCompany();

        $companyBusinessUnitEntity = SpyCompanyBusinessUnitQuery::create()
            ->filterByName($dataSet[static::KEY_NAME])
            ->filterByFkCompany($idCompany)
            ->findOneOrCreate();

        $companyBusinessUnitEntity->fromArray($dataSet->getArrayCopy());

        $companyBusinessUnitEntity->save();
    }

    /**
     * @throws \Spryker\Zed\CompanyBusinessUnitDataImport\Exception\CompanyNotFoundException
     *
     * @return int
     */
    protected function getIdCompany(): int
    {
        if ($this->idCompany === null) {
            $companyEntity = SpyCompanyQuery::create()->findOne();
            if (!$companyEntity) {
                throw new CompanyNotFoundException();
            }
            $this->idCompany = $companyEntity->getIdCompany();
        }

        return $this->idCompany;
    }
}
