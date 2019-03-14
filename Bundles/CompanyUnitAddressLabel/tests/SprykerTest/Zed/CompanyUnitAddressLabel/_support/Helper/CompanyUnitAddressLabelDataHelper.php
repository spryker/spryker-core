<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabel\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyUnitAddressLabelBuilder;
use Generated\Shared\DataBuilder\CompanyUnitAddressLabelCollectionBuilder;
use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressLabelTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabel;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelQuery;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelToCompanyUnitAddress;
use Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface;
use Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepository;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\CompanyBusinessUnit\Helper\CompanyBusinessUnitHelper;
use SprykerTest\Zed\CompanyUnitAddress\Helper\CompanyUnitAddressDataHelper;

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
     * @param \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer $labelCollection
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function haveLabelAddressRelations(CompanyUnitAddressLabelCollectionTransfer $labelCollection, CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $labelAddressRelation = new SpyCompanyUnitAddressLabelToCompanyUnitAddress();
        $labelAddressRelation->setFkCompanyUnitAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress());
        foreach ($labelCollection->getLabels() as $labelTransfer) {
            $labelAddressRelation->setFkCompanyUnitAddressLabel($labelTransfer->getIdCompanyUnitAddressLabel());
            $labelAddressRelation->save();
        }
    }

    /**
     * @param array $labelCollectionSeed
     * @param array $labelsSeed
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function haveLabelCollection(array $labelCollectionSeed = [], array $labelsSeed = []): CompanyUnitAddressLabelCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer $companyUnitAddressLabelCollection */
        $companyUnitAddressLabelCollection = (new CompanyUnitAddressLabelCollectionBuilder($labelCollectionSeed))
            ->withLabels($labelsSeed)
            ->build();

        foreach ($companyUnitAddressLabelCollection->getLabels() as $companyUnitAddressLabelEntityTransfer) {
            $spyCompanyUnitAddressLabelEntity = new SpyCompanyUnitAddressLabel();
            $spyCompanyUnitAddressLabelEntity->setName($companyUnitAddressLabelEntityTransfer->getName())
                ->save();
            $companyUnitAddressLabelEntityTransfer->setIdCompanyUnitAddressLabel($spyCompanyUnitAddressLabelEntity->getIdCompanyUnitAddressLabel());
        }

        return $companyUnitAddressLabelCollection;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getCompanyUnitAddressTransfer(array $seed = []): CompanyUnitAddressTransfer
    {
        if (empty($seed['fkCompany'])) {
            $seed['fkCompany'] = $this->getCompanyBusinessUnitHelper()->haveCompanyBusinessUnitWithCompany()->getFkCompany();
        }

        /** @var \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer */
        $companyUnitAddressTransfer = $this->getCompanyUnitAddressHelper()->haveCompanyUnitAddress($seed);

        return $companyUnitAddressTransfer;
    }

    /**
     * @return \SprykerTest\Zed\CompanyUnitAddress\Helper\CompanyUnitAddressDataHelper
     */
    protected function getCompanyUnitAddressHelper(): CompanyUnitAddressDataHelper
    {
        /** @var \SprykerTest\Zed\CompanyUnitAddress\Helper\CompanyUnitAddressDataHelper $companyUnitAddressDataHelper */
        $companyUnitAddressDataHelper = $this->getModule('\\' . CompanyUnitAddressDataHelper::class);
        return $companyUnitAddressDataHelper;
    }

    /**
     * @return \SprykerTest\Zed\CompanyBusinessUnit\Helper\CompanyBusinessUnitHelper
     */
    protected function getCompanyBusinessUnitHelper(): CompanyBusinessUnitHelper
    {
        /** @var \SprykerTest\Zed\CompanyBusinessUnit\Helper\CompanyBusinessUnitHelper $companyBusinessUnitHelper */
        $companyBusinessUnitHelper = $this->getModule('\\' . CompanyBusinessUnitHelper::class);
        return $companyBusinessUnitHelper;
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface
     */
    public function getCompanyUnitAddressLabelFacade(): CompanyUnitAddressLabelFacadeInterface
    {
        return $this->getLocator()->companyUnitAddressLabel()->facade();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepository
     */
    protected function createLabelRepository(): CompanyUnitAddressLabelRepository
    {
        return new CompanyUnitAddressLabelRepository();
    }
}
