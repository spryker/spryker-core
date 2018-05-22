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
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantRelationshipHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function haveMerchantRelationship(array $seedData): MerchantRelationshipTransfer
    {
        $merchantRelationship = (new MerchantRelationshipBuilder($seedData))->build();
        $merchantRelationship->setIdMerchantRelationship(null);

        return $this->getLocator()->merchantRelationship()->facade()->createMerchantRelationship($merchantRelationship);
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
}
