<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationship\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantRelationshipBuilder;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantRelationshipHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function haveMerchantRelationship(array $seedData): MerchantRelationshipTransfer
    {
        $merchantRelationship = (new MerchantRelationshipBuilder($seedData))->build();
        $merchantRelationship->setIdMerchantRelationship(null);

        $merchantRelationshipTransfer = $this->getLocator()
            ->merchantRelationship()
            ->facade()
            ->createMerchantRelationship($merchantRelationship);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($merchantRelationshipTransfer) {
            $this->cleanupMerchantRelationship($merchantRelationshipTransfer);
        });

        return $merchantRelationshipTransfer;
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

        $this->getLocator()
            ->merchantRelationship()
            ->facade()
            ->deleteMerchantRelationship($merchantRelationshipTransfer);
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function assertMerchantRelationshipNotExists(int $idMerchantRelationship): void
    {
        $query = $this->getMerchantRelationshipQuery()
            ->filterByIdMerchantRelationship($idMerchantRelationship);

        $this->assertSame(0, $query->count());
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function getMerchantRelationshipQuery(): SpyMerchantRelationshipQuery
    {
        return SpyMerchantRelationshipQuery::create();
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function assertMerchantRelationshipToCompanyBusinessUnitNotExists(int $idMerchantRelationship): void
    {
        $query = $this->getMerchantRelationshipToCompanyBusinessUnitQuery()
            ->filterByFkMerchantRelationship($idMerchantRelationship);

        $this->assertSame(0, $query->count());
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery
     */
    protected function getMerchantRelationshipToCompanyBusinessUnitQuery(): SpyMerchantRelationshipToCompanyBusinessUnitQuery
    {
        return SpyMerchantRelationshipToCompanyBusinessUnitQuery::create();
    }
}
