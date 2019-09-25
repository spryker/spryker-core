<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlock\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CmsBlock
 * @group Business
 * @group Facade
 * @group CmsBlockFacadeTest
 * Add your own group annotations below this line
 */
class CmsBlockFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CmsBlock\CmsBlockBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindCmsBlockById()
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $cmsBlockTransfer = $this->createCmsBlockFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        $this->assertInstanceOf(CmsBlockTransfer::class, $cmsBlockTransfer);
    }

    /**
     * @return void
     */
    public function testActivateById()
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock(['is_active' => false]);

        $this->assertFalse($cmsBlockTransfer->getIsActive());

        $this->createCmsBlockFacade()
            ->activateById($cmsBlockTransfer->getIdCmsBlock());

        $cmsBlockTransfer = $this->createCmsBlockFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        $this->assertTrue($cmsBlockTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testDeactivateById()
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock(['is_active' => true]);

        $this->assertTrue($cmsBlockTransfer->getIsActive());

        $this->createCmsBlockFacade()
            ->deactivateById($cmsBlockTransfer->getIdCmsBlock());

        $cmsBlockTransfer = $this->createCmsBlockFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        $this->assertFalse($cmsBlockTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function testUpdateCmsBlock()
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();
        $cmsBlockTransfer->setName('Test name');

        $this->createCmsBlockFacade()
            ->updateCmsBlock($cmsBlockTransfer);

        $cmsBlockTransfer = $this->createCmsBlockFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        $this->assertEquals('Test name', $cmsBlockTransfer->getName());
    }

    /**
     * @return void
     */
    public function testCreateCmsBlock()
    {
        $cmsBlockTemplateTransfer = $this->tester->haveCmsBlockTemplate();

        $cmsBlockTransfer = new CmsBlockTransfer();
        $cmsBlockTransfer->setName('Test name');
        $cmsBlockTransfer->setFkTemplate($cmsBlockTemplateTransfer->getIdCmsBlockTemplate());

        $this->createCmsBlockFacade()
            ->createCmsBlock($cmsBlockTransfer);

        $this->assertNotEmpty($cmsBlockTransfer->getIdCmsBlock());
    }

    /**
     * @return void
     */
    public function testFindGlossaryPlaceholders()
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $translation = new CmsBlockGlossaryPlaceholderTranslationTransfer();
        $translation->setFkLocale(66);
        $translation->setTranslation('Test translation');

        $placeholder = new CmsBlockGlossaryPlaceholderTransfer();
        $placeholder->addTranslation($translation);
        $placeholder->setPlaceholder('placeholder');
        $placeholder->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock());

        $glossary = new CmsBlockGlossaryTransfer();
        $glossary->addGlossaryPlaceholder($placeholder);

        $this->createCmsBlockFacade()
            ->saveGlossary($glossary);

        $glossary = $this->createCmsBlockFacade()
            ->findGlossary($cmsBlockTransfer->getIdCmsBlock());

        foreach ($glossary->getGlossaryPlaceholders() as $placeholder) {
            $this->assertEquals('placeholder', $placeholder->getPlaceholder());
        }
    }

    /**
     * @return void
     */
    public function testSaveGlossary()
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();

        $translation = new CmsBlockGlossaryPlaceholderTranslationTransfer();
        $translation->setFkLocale(66);
        $translation->setTranslation('Test translation');

        $placeholder = new CmsBlockGlossaryPlaceholderTransfer();
        $placeholder->addTranslation($translation);
        $placeholder->setPlaceholder('placeholder');
        $placeholder->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock());

        $glossary = new CmsBlockGlossaryTransfer();
        $glossary->addGlossaryPlaceholder($placeholder);

        $this->createCmsBlockFacade()
            ->saveGlossary($glossary);

        $glossary = $this->createCmsBlockFacade()
            ->findGlossary($cmsBlockTransfer->getIdCmsBlock());

        $this->assertNotEmpty($glossary);
    }

    /**
     * @return void
     */
    public function testCreateTemplate()
    {
        $this->createCmsBlockFacade()
            ->createTemplate('test name', 'test path');

        $cmsBlockTemplateTransfer = $this->createCmsBlockFacade()
            ->findTemplate('test path');

        $this->assertNotEmpty($cmsBlockTemplateTransfer);
    }

    /**
     * @return void
     */
    public function testFindTemplate()
    {
        $this->createCmsBlockFacade()
            ->createTemplate('test name', 'test path');

        $cmsBlockTemplateTransfer = $this->createCmsBlockFacade()
            ->findTemplate('test path');

        $this->assertEquals('test name', $cmsBlockTemplateTransfer->getTemplateName());
    }

    /**
     * @dataProvider relationUpdate
     *
     * @param int[] $originalRelation
     * @param int[] $modifiedRelation
     *
     * @return void
     */
    public function testUpdateCmsBlockUpdatesStoreRelation(array $originalRelation, array $modifiedRelation)
    {
        // Assign
        $cmsBlockTransfer = $this->tester->haveCmsBlock(
            [
                CmsBlockTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => $originalRelation,
                ],
            ]
        );

        $this->createCmsBlockFacade()->updateCmsBlock($cmsBlockTransfer);

        // Act
        $cmsBlockTransfer->getStoreRelation()->setIdStores($modifiedRelation);
        $this->createCmsBlockFacade()
            ->updateCmsBlock($cmsBlockTransfer);

        $cmsBlockTransfer = $this->createCmsBlockFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        $resultIdStores = $cmsBlockTransfer->getStoreRelation()->getIdStores();

        // Assert
        sort($resultIdStores);
        $this->assertEquals($modifiedRelation, $resultIdStores);
    }

    /**
     * @return array
     */
    public function relationUpdate()
    {
        return [
            [
                [1, 2, 3], [2],
            ],
            [
                [1], [1, 2],
            ],
            [
                [2], [1, 3],
            ],
        ];
    }

    /**
     * @return void
     */
    public function testCreateCmsBlockSavesStoreRelation()
    {
        // Assign
        $expectedIdStores = [1, 3];
        $cmsBlockTransfer = $this->tester->haveCmsBlock([
            CmsBlockTransfer::STORE_RELATION => [StoreRelationTransfer::ID_STORES => $expectedIdStores],
        ]);

        // Act
        $cmsBlockTransfer = $this->createCmsBlockFacade()->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());
        $resultIdStores = $cmsBlockTransfer->getStoreRelation()->getIdStores();

        // Assert
        sort($resultIdStores);
        $this->assertEquals($expectedIdStores, $resultIdStores);
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface
     */
    protected function createCmsBlockFacade()
    {
        return $this->tester->getLocator()->cmsBlock()->facade();
    }
}
