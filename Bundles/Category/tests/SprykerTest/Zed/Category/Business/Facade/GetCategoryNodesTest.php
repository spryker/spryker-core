<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use SprykerTest\Zed\Category\CategoryBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Business
 * @group Facade
 * @group GetCategoryNodesTest
 * Add your own group annotations below this line
 */
class GetCategoryNodesTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Category\CategoryBusinessTester
     */
    protected CategoryBusinessTester $tester;

    /**
     * @return void
     */
    public function testWillReturnCorrectNodeTransfersWithAvailableLocalizedAttributes(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->createCategoryTransferWithLocalizedAttributes();
        $idCategoryNode = $categoryTransfer->getCategoryNode()->getIdCategoryNodeOrFail();
        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->setCategoryNodeIds([$idCategoryNode])
            ->setWithRelations(true);

        // Act
        $nodeCollectionTransfer = $this->tester->getFacade()->getCategoryNodes($categoryNodeCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\NodeTransfer $nodeTransfer */
        $nodeTransfer = $nodeCollectionTransfer->getNodes()->getIterator()->current();

        $this->assertCount(1, $nodeCollectionTransfer->getNodes());
        $this->assertEqualsCanonicalizing(
            $this->getLocalizedAttributeNames($categoryTransfer->getLocalizedAttributes()),
            $this->getLocalizedAttributeNames($nodeTransfer->getCategoryOrFail()->getLocalizedAttributes()),
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributes
     *
     * @return list<string>
     */
    protected function getLocalizedAttributeNames(ArrayObject $localizedAttributes): array
    {
        $localizedAttributeNames = [];

        foreach ($localizedAttributes as $localizedAttribute) {
            $localizedAttributeNames[] = $localizedAttribute->getNameOrFail();
        }

        return $localizedAttributeNames;
    }
}
