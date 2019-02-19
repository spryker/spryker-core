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
use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressLabelTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressLabelEntityTransfer;
use Generated\Shared\Transfer\SpyRegionEntityTransfer;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabel;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelQuery;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddress;
use Orm\Zed\Country\Persistence\SpyRegionQuery;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface;
use Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface;
use Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepository;
use Spryker\Zed\Country\Business\CountryFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyUnitAddressLabelDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelTransfer
     */
    public function haveCompanyUnitAddressLabel(array $seed = []): CompanyUnitAddressLabelTransfer
    {
        $companyUnitAddressLabelBuilder = new CompanyUnitAddressLabelBuilder($seed);
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressLabelTransfer $companyUnitAddressLabelTransfer */
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
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function haveCompanyUnitAddressTransfer(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyUnitAddressTransfer
    {
        $countryTransfer = $this->haveCountryTransfer();
        $regionTransfer = $this->haveRegionTransfer();

        /** @var \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer */
        $companyUnitAddressTransfer = (new CompanyUnitAddressBuilder())
            ->build();

        $companyUnitAddressTransfer->setFkCompany($companyBusinessUnitTransfer->getFkCompany());
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
    public function haveCountryTransfer(): CountryTransfer
    {
        /** @var \Generated\Shared\Transfer\CountryTransfer $countryTransfer */
        $countryTransfer = (new CountryBuilder())->build();

        return $this->getCountryFacade()->getCountryByIso2Code(
            $countryTransfer->getIso2Code()
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
    public function haveRegionTransfer(): SpyRegionEntityTransfer
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
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function haveLabelCollection(): CompanyUnitAddressLabelCollectionTransfer
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
    public function getCompanyUnitAddressLabelFacade(): CompanyUnitAddressLabelFacadeInterface
    {
        return $this->getLocator()->companyUnitAddressLabel()->facade();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface
     */
    protected function getCompanyUnitAddressFacade(): CompanyUnitAddressFacadeInterface
    {
        return $this->getLocator()->companyUnitAddress()->facade();
    }

    /**
     * @return \Spryker\Zed\Country\Business\CountryFacadeInterface
     */
    protected function getCountryFacade(): CountryFacadeInterface
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
    protected function getCompanyBusinessUnitFacade(): CompanyBusinessUnitFacadeInterface
    {
        return $this->getLocator()->companyBusinessUnit()->facade();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepository
     */
    protected function createLabelRepository(): CompanyUnitAddressLabelRepository
    {
        return new CompanyUnitAddressLabelRepository();
    }
}
