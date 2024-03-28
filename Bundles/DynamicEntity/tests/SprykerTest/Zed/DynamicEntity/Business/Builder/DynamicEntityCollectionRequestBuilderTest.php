<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DynamicEntity\Business\Builder;

use Codeception\Test\Unit;
use Spryker\Zed\DynamicEntity\Business\DynamicEntityBusinessFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DynamicEntity
 * @group Business
 * @group Builder
 * @group DynamicEntityCollectionRequestBuilderTest
 * Add your own group annotations below this line
 */
class DynamicEntityCollectionRequestBuilderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\DynamicEntity\DynamicEntityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBuildRelationChainsFromDynamicEntityCollectionRequestGeneratesCorrectChain(): void
    {
        //Arrange
        $builder = (new DynamicEntityBusinessFactory())->createDynamicEntityCollectionRequestBuilder();
        $dynamicEntityCollectionRequestTransfer = $this->tester->createDynamicEntityCollectionRequestTransferWithComplexData();

        //Act
        $relationChains = $builder->buildRelationChainsFromDynamicEntityCollectionRequest($dynamicEntityCollectionRequestTransfer);

        //Assert
        $this->assertSame(
            array_values($relationChains),
            array_values($this->tester::RELATION_TEST_CHAINS),
        );
    }
}
