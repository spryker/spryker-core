<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model;

use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelQuery;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Exception\CompanyUnitAddressLabelNotFoundException;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Exception\CompanyUnitAddressNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CompanyUnitAddressLabelRelationWriterStep implements DataImportStepInterface
{
    const KEY_LABEL_NAME = 'label_name';
    const KEY_ADDRESS_KEY = 'address_key';

    /**
     * @var array
     */
    protected $idCompanyUnitAddressCache = [];

    /**
     * @var array
     */
    protected $idCompanyUnitAddressLabelCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $idCompanyUnitAddress = $this->getIdCompanyUnitAddress($dataSet);
        $idCompanyUnitAddressLabel = $this->getIdCompanyUnitAddressLabel($dataSet);

        $companyUnitAddressLabelToCompanyUnitAddressEntity = SpyCompanyUnitAddressLabelToCompanyUnitAddressQuery::create()
            ->filterByFkCompanyUnitAddress($idCompanyUnitAddress)
            ->filterByFkCompanyUnitAddressLabel($idCompanyUnitAddressLabel)
            ->findOneOrCreate();

        $companyUnitAddressLabelToCompanyUnitAddressEntity->save();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Exception\CompanyUnitAddressNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyUnitAddress(DataSetInterface $dataSet): int
    {
        $addressKey = $dataSet[static::KEY_ADDRESS_KEY];
        if (!isset($this->idCompanyUnitAddressCache[$addressKey])) {
            $companyUnitAddressQuery = new SpyCompanyUnitAddressQuery();
            $companyUnitAddressEntity = $companyUnitAddressQuery->findOneByKey($addressKey);
            if (!$companyUnitAddressEntity) {
                throw new CompanyUnitAddressNotFoundException(sprintf('Could not find CompanyUnitAddress with key "%s".', $addressKey));
            }

            $this->idCompanyUnitAddressCache[$addressKey] = $companyUnitAddressEntity->getIdCompanyUnitAddress();
        }

        return $this->idCompanyUnitAddressCache[$addressKey];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Exception\CompanyUnitAddressLabelNotFoundException
     *
     * @return int
     */
    protected function getIdCompanyUnitAddressLabel(DataSetInterface $dataSet): int
    {
        $labelName = $dataSet[static::KEY_LABEL_NAME];
        if (!isset($this->idCompanyUnitAddressLabelCache[$labelName])) {
            $companyUnitAddressLabelQuery = new SpyCompanyUnitAddressLabelQuery();
            $companyUnitAddressLabelEntity = $companyUnitAddressLabelQuery->findOneByName($labelName);
            if (!$companyUnitAddressLabelEntity) {
                throw new CompanyUnitAddressLabelNotFoundException(sprintf('Could not find CompanyUnitAddressLabel with name "%s".', $labelName));
            }

            $this->idCompanyUnitAddressLabelCache[$labelName] = $companyUnitAddressLabelEntity->getIdCompanyUnitAddressLabel();
        }

        return $this->idCompanyUnitAddressLabelCache[$labelName];
    }
}
