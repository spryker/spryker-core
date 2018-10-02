<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductMerchantRelationship\Business;

use Codeception\Test\Unit;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PriceProductMerchantRelationship
 * @group Business
 * @group Facade
 * @group PriceProductMerchantRelationshipFacadeTest
 * Add your own group annotations below this line
 */
class PriceProductMerchantRelationshipFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PriceProductMerchantRelationship\PriceProductMerchantRelationshipBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDeletePriceProductMerchantRelationshipByIdPriceProductStore()
    {
        $idPriceProductStore = $this
            ->tester
            ->createPriceProductMerchantRelationship()
            ->getMoneyValue()
            ->getIdEntity();

        $this->tester->getFacade()
            ->deletePriceProductMerchantRelationshipByIdPriceProductStore($idPriceProductStore);

        $priceProductMerchantRelationshipQuery = $this->getPriceProductMerchantRelationshipQuery()
            ->filterByFkPriceProductStore($idPriceProductStore);

        $this->assertEquals(0, $priceProductMerchantRelationshipQuery->count());
    }

    /**
     * @return \Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery
     */
    protected function getPriceProductMerchantRelationshipQuery(): SpyPriceProductMerchantRelationshipQuery
    {
        return SpyPriceProductMerchantRelationshipQuery::create();
    }
}
