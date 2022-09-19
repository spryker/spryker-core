<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsSlotGui\Communication\Table;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTemplateTableMap;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsSlotGui
 * @group Communication
 * @group Table
 * @group TemplateTableQueryTest
 * Add your own group annotations below this line
 */
class TemplateTableQueryTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsSlotGui\CmsSlotGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFetchDataCollectsCorrectCmsSlotTemplates(): void
    {
        // Arrange
        $cmsSlotTemplateTransfer1 = $this->tester->haveCmsSlotTemplateInDb([
            CmsSlotTemplateTransfer::NAME => 'Test Template 1',
            CmsSlotTemplateTransfer::PATH => '@TestModule/views/test/test_template_1.twig',
        ]);
        $cmsSlotTemplateTransfer2 = $this->tester->haveCmsSlotTemplateInDb([
            CmsSlotTemplateTransfer::NAME => 'Test Template 2',
            CmsSlotTemplateTransfer::PATH => '@TestModule/views/test/test_template_2.twig',
        ]);

        $cmsSlotTransfer1 = $this->tester->haveCmsSlotInDb([
            CmsSlotTransfer::KEY => 'test-slot-key-1',
        ]);
        $cmsSlotTransfer2 = $this->tester->haveCmsSlotInDb([
            CmsSlotTransfer::KEY => 'test-slot-key-2',
        ]);

        $this->tester->haveCmsSlotCmsToSlotTemplateConnection($cmsSlotTransfer1->getIdCmsSlot(), $cmsSlotTemplateTransfer1->getIdCmsSlotTemplate());
        $this->tester->haveCmsSlotCmsToSlotTemplateConnection($cmsSlotTransfer2->getIdCmsSlot(), $cmsSlotTemplateTransfer2->getIdCmsSlotTemplate());

        $templateTableMock = new TemplateTableMock(SpyCmsSlotTemplateQuery::create());

        // Act
        $result = $templateTableMock->fetchData();

        // Assert
        $this->assertNotEmpty($result);
        $resultCmsSlotTemplateIds = array_column($result, SpyCmsSlotTemplateTableMap::COL_ID_CMS_SLOT_TEMPLATE);
        $this->assertContains((string)$cmsSlotTemplateTransfer1->getIdCmsSlotTemplate(), $resultCmsSlotTemplateIds);
        $this->assertContains((string)$cmsSlotTemplateTransfer2->getIdCmsSlotTemplate(), $resultCmsSlotTemplateIds);
    }
}
