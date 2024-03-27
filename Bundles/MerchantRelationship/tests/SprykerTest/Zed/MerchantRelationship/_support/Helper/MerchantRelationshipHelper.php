<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationship\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantRelationshipBuilder;
use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery;
use Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantRelationshipHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;
    use DependencyHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function haveMerchantRelationship(array $seedData): MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer = (new MerchantRelationshipBuilder($seedData))->build();
        $merchantRelationshipTransfer->setIdMerchantRelationship(null);

        $merchantRelationshipTransfer = $this->createOrUpdateMerchantRelationship($merchantRelationshipTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantRelationshipTransfer): void {
            $this->cleanupMerchantRelationship($merchantRelationshipTransfer);
        });

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function createOrUpdateMerchantRelationship(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $foundMerchantRelationshipTransfer = $this->getMerchantRelationshipFacade()
            ->findMerchantRelationshipByKey($merchantRelationshipTransfer);

        $merchantRelationshipRequestTransfer = (new MerchantRelationshipRequestTransfer())
            ->setMerchantRelationship($merchantRelationshipTransfer);

        if ($foundMerchantRelationshipTransfer) {
            $merchantRelationshipTransfer->setIdMerchantRelationship(
                $foundMerchantRelationshipTransfer->getIdMerchantRelationship(),
            );

            $merchantRelationshipResponseTransfer = $this->getMerchantRelationshipFacade()->updateMerchantRelationship(
                $merchantRelationshipTransfer,
                $merchantRelationshipRequestTransfer,
            );

            return $merchantRelationshipResponseTransfer->getMerchantRelationshipOrFail();
        }

        $merchantRelationshipResponseTransfer = $this->getMerchantRelationshipFacade()->createMerchantRelationship(
            $merchantRelationshipTransfer,
            $merchantRelationshipRequestTransfer,
        );

        return $merchantRelationshipResponseTransfer->getMerchantRelationshipOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return void
     */
    protected function cleanupMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): void {
        $this->debug(sprintf('Deleting Merchant Relationship: %d', $merchantRelationshipTransfer->getIdMerchantRelationship()));

        $this->getMerchantRelationshipToCompanyBusinessUnitQuery()
            ->filterByFkMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationshipOrFail())
            ->delete();
        $this->getMerchantRelationshipQuery()
            ->filterByIdMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationshipOrFail())
            ->delete();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface
     */
    protected function getMerchantRelationshipFacade(): MerchantRelationshipFacadeInterface
    {
        return $this->getLocator()->merchantRelationship()->facade();
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function getMerchantRelationshipQuery(): SpyMerchantRelationshipQuery
    {
        return SpyMerchantRelationshipQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery
     */
    protected function getMerchantRelationshipToCompanyBusinessUnitQuery(): SpyMerchantRelationshipToCompanyBusinessUnitQuery
    {
        return SpyMerchantRelationshipToCompanyBusinessUnitQuery::create();
    }
}
