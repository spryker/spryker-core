<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipProductList\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationshipProductList
 * @group Business
 * @group Facade
 * @group MerchantRelationshipProductListFacadeTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipProductListFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantRelationshipProductList\MerchantRelationshipProductListBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testClearMerchantRelationshipFromProductListWillClearProductListsRelationshipToMerchant(): void
    {
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $productListTransfer = $this->tester->createProductListWithMerchantRelationship($merchantRelationshipTransfer);

        /** @var \Spryker\Zed\MerchantRelationshipProductList\Business\MerchantRelationshipProductListFacadeInterface $merchantRelationshipProductListFacade */
        $merchantRelationshipProductListFacade = $this->tester->getFacade();
        $merchantRelationshipProductListFacade->clearMerchantRelationshipFromProductLists($merchantRelationshipTransfer);

        $actualProductListTransfer = $this->tester->getProductListFacade()->getProductListById($productListTransfer);

        $this->assertNotEquals(
            $merchantRelationshipTransfer->getIdMerchantRelationship(),
            $actualProductListTransfer->getFkMerchantRelationship()
        );
        $this->assertNull($actualProductListTransfer->getFkMerchantRelationship());

        $this->tester->getProductListFacade()->deleteProductList($productListTransfer);
    }
}
