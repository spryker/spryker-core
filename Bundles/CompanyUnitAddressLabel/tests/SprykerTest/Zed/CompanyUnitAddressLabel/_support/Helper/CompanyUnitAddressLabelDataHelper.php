<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabel\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyUnitAddressBuilder;
use Generated\Shared\DataBuilder\CompanyUnitAddressLabelBuilder;
use Generated\Shared\DataBuilder\CountryBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressLabelEntityTransfer;
use Generated\Shared\Transfer\SpyRegionEntityTransfer;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabel;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelQuery;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddress;
use Orm\Zed\Country\Persistence\SpyRegionQuery;
use Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepository;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyUnitAddressLabelDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelTransfer
     */
    public function haveCompanyUnitAddressLabel(array $seed = [])
    {
        $companyUnitAddressLabelBuilder = new CompanyUnitAddressLabelBuilder($seed);
        $companyUnitAddressLabelTransfer = $companyUnitAddressLabelBuilder->build();

        $companyUnitAddressLabelQuery = new SpyCompanyUnitAddressLabelQuery();
        $companyUnitAddressLabelEntity = $companyUnitAddressLabelQuery
            ->filterByName($companyUnitAddressLabelTransfer->getName())
            ->findOneOrCreate();

        $companyUnitAddressLabelEntity->save();

        $companyUnitAddressLabelTransfer->fromArray($companyUnitAddressLabelEntity->toArray(), true);

        return $companyUnitAddressLabelTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function haveCompanyUnitAddressTransfer()
    {
        $countryTransfer = $this->haveCountryTransfer();
        $regionTransfer = $this->haveRegionTransfer();
        $companyTransfer = $this->haveCompanyTransfer();
        $companyBusinessUnitTransfer = $this->haveCompanyBusinessUnit();

        $companyUnitAddressTransfer = (new CompanyUnitAddressBuilder())
            ->build();

        $companyUnitAddressTransfer->setFkCompany($companyTransfer->getIdCompany());
        $companyUnitAddressTransfer->setFkCountry($countryTransfer->getIdCountry());
        $companyUnitAddressTransfer->setFkRegion($regionTransfer->getIdRegion());
        $companyUnitAddressTransfer->setFkCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());

        $response = $this->getCompanyUnitAddressFacade()
            ->create($companyUnitAddressTransfer);

        return $response->getCompanyUnitAddressTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function haveCountryTransfer()
    {
        return $this->getCountryFacade()->getCountryByIso2Code(
            (new CountryBuilder())->build()->getIso2Code()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function haveLabelAddressRelations(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $labelAddressRelation = new SpyCompanyUnitAddressLabelToCompanyUnitAddress();
        $labels = $this->haveLabelCollection();
        $labelAddressRelation->setFkCompanyUnitAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());
        foreach ($labels->getLabels() as $labelTransfer) {
            $labelAddressRelation->setFkCompanyUnitAddressLabel($labelTransfer->getIdCompanyUnitAddressLabel());
            $labelAddressRelation->save();
        }
    }

    /**
     * @return \Generated\Shared\Transfer\SpyRegionEntityTransfer
     */
    public function haveRegionTransfer()
    {
        $countryTransfer = $this->haveCountryTransfer();
        $regionEntity = SpyRegionQuery::create()
            ->filterByName('test region')
            ->filterByIso2Code(
                $countryTransfer->getIso2Code()
            )->filterByFkCountry(
                $countryTransfer->getIdCountry()
            )->findOneOrCreate();

        return (new SpyRegionEntityTransfer())
            ->fromArray($regionEntity->toArray());
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function haveCompanyTransfer()
    {
        $response = $this->getCompanyFacade()
            ->create(
                (new CompanyTransfer())
                    ->setStatus('approved')
                    ->setName('Test company')
                    ->setIsActive(true)
                    ->setInitialUserTransfer((new CompanyUserTransfer()))
            );

        return $response->getCompanyTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function haveCompanyBusinessUnit()
    {
        $companyTransfer = $this->haveCompanyTransfer();
        $response = $this->getCompanyBusinessUnitFacade()
            ->create(
                (new CompanyBusinessUnitTransfer())
                    ->setName('test business unit')
                    ->setEmail('test@spryker.com')
                    ->setPhone(1234567890)
                    ->setFkCompany($companyTransfer->getIdCompany())
            );

        return $response->getCompanyBusinessUnitTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function haveLabelCollection()
    {
        $labelEntity = new SpyCompanyUnitAddressLabel();
        $labelEntity->setName("test label");
        $labelEntity->save();

        return (new CompanyUnitAddressLabelCollectionTransfer())
            ->setLabels(
                new ArrayObject(
                    [
                        (new SpyCompanyUnitAddressLabelEntityTransfer())
                            ->setName($labelEntity->getName())
                            ->setIdCompanyUnitAddressLabel($labelEntity->getIdCompanyUnitAddressLabel()),
                    ]
                )
            );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface
     */
    public function getCompanyUnitAddressLabelFacade()
    {
        return $this->getLocator()->companyUnitAddressLabel()->facade();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface
     */
    protected function getCompanyUnitAddressFacade()
    {
        return $this->getLocator()->companyUnitAddress()->facade();
    }

    /**
     * @return \Spryker\Zed\Country\Business\CountryFacadeInterface
     */
    protected function getCountryFacade()
    {
        return $this->getLocator()->country()->facade();
    }

    /**
     * @return \Spryker\Zed\Company\Business\CompanyFacadeInterface
     */
    protected function getCompanyFacade()
    {
        return $this->getLocator()->company()->facade();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface
     */
    protected function getCompanyBusinessUnitFacade()
    {
        return $this->getLocator()->companyBusinessUnit()->facade();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepository
     */
    protected function createLabelRepository()
    {
        return new CompanyUnitAddressLabelRepository();
    }
}
