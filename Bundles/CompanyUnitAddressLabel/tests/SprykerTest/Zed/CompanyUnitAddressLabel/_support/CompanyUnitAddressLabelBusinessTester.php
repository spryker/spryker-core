<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddressLabel;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\CompanyUnitAddressLabelBuilder;
use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressLabelEntityTransfer;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabel;
use Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabelQuery;
use Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class CompanyUnitAddressLabelBusinessTester extends Actor
{
    use _generated\CompanyUnitAddressLabelBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param array $labelsSeed
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function buildCompanyUnitAddressLabelCollection(array $labelsSeed = []): CompanyUnitAddressLabelCollectionTransfer
    {
        $labelEntity = $this->buildCompanyUnitAddressLabelEntity($labelsSeed);

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
     * @param array $seed
     *
     * @return \Orm\Zed\CompanyUnitAddressLabel\Persistence\SpyCompanyUnitAddressLabel
     */
    public function buildCompanyUnitAddressLabelEntity(array $seed = []): SpyCompanyUnitAddressLabel
    {
        $companyUnitAddressLabelBuilder = new CompanyUnitAddressLabelBuilder($seed);
        /** @var \Generated\Shared\Transfer\CompanyUnitAddressLabelTransfer $companyUnitAddressLabelTransfer */
        $companyUnitAddressLabelTransfer = $companyUnitAddressLabelBuilder->build();

        $companyUnitAddressLabelQuery = new SpyCompanyUnitAddressLabelQuery();
        $companyUnitAddressLabelEntity = $companyUnitAddressLabelQuery
            ->filterByName($companyUnitAddressLabelTransfer->getName())
            ->findOneOrCreate();

        $companyUnitAddressLabelEntity->save();

        return $companyUnitAddressLabelEntity;
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface
     */
    public function getCompanyUnitAddressLabelFacade(): CompanyUnitAddressLabelFacadeInterface
    {
        /** @var \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface $companyUnitAddressLabelFacade */
        $companyUnitAddressLabelFacade = $this->getFacade();
        return $companyUnitAddressLabelFacade;
    }
}
