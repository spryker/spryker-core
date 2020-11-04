<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelationGui\Communication\QueryCreator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\RuleQueryDataProviderTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\ProductRelationGui\Communication\Provider\MappingProviderInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToPropelQueryBuilderQueryContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductRelationGui
 * @group Communication
 * @group QueryCreator
 * @group RuleQueryCreatorQueryTest
 * Add your own group annotations below this line
 */
class RuleQueryCreatorQueryTest extends Unit
{
    protected const COL_ID_PRODUCT_ABSTRACT = 'IdProductAbstract';

    /**
     * @var \SprykerTest\Zed\ProductRelationGui\ProductRelationGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPrepareQueryCreatesQueryThatReturnsCorrectData(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveLocalizedCategory();
        $productTransfer = $this->tester->haveFullProduct();
        $this->tester->assignProductToCategory($categoryTransfer->getIdCategory(), $productTransfer->getFkProductAbstract());

        $ruleQueryDataProviderTransfer = (new RuleQueryDataProviderTransfer())->setIdProductAbstract($productTransfer->getFkProductAbstract());
        $productRelationTransfer = (new ProductRelationTransfer())->setQueryDataProvider($ruleQueryDataProviderTransfer);
        $ruleQueryCreatorMock = new RuleQueryCreatorMock(
            $this->getLocaleFacadeMock(),
            SpyProductAbstractQuery::create(),
            $this->getMappingProviderMock(),
            $this->getPropelQueryBuilderQueryContainer()
        );

        // Act
        $result = $ruleQueryCreatorMock->createQuery($productRelationTransfer)->find()->toArray();

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals($productTransfer->getFkProductAbstract(), $result[0][static::COL_ID_PRODUCT_ABSTRACT]);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface
     */
    protected function getLocaleFacadeMock(): ProductRelationGuiToLocaleFacadeInterface
    {
        $localeFacadeMock = $this->getMockBuilder(ProductRelationGuiToLocaleFacadeInterface::class)->getMock();
        $localeFacadeMock->method('getCurrentLocale')->willReturn($this->tester->getCurrentLocale());

        return $localeFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductRelationGui\Communication\Provider\MappingProviderInterface
     */
    protected function getMappingProviderMock(): MappingProviderInterface
    {
        return $this->getMockBuilder(MappingProviderInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductRelationGui\Dependency\QueryContainer\ProductRelationGuiToPropelQueryBuilderQueryContainerInterface
     */
    protected function getPropelQueryBuilderQueryContainer(): ProductRelationGuiToPropelQueryBuilderQueryContainerInterface
    {
        return $this->getMockBuilder(ProductRelationGuiToPropelQueryBuilderQueryContainerInterface::class)->getMock();
    }
}
