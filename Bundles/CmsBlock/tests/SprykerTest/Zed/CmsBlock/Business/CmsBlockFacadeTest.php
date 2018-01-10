<?php

namespace SprykerTest\Zed\CmsBlock\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;

/**
 * Auto-generated group annotations
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
     * @var \SprykerTest\Zed\CmsBlock\BusinessTester
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
     * @return void
     */
    public function testGetCmsBlockStoreRelationRetrievesRelatedStores()
    {
        // Assign
        $idCmsBlock = 1;
        $relatedStores = [1, 3];
        $productAbstractRelationRequest = (new StoreRelationTransfer())
            ->setIdEntity($idCmsBlock);
        $expectedResult = (new StoreRelationTransfer())
            ->setIdEntity($idCmsBlock)
            ->setIdStores($relatedStores)
            ->setStores(new ArrayObject());
        $cmsBlockFacade = $this->createCmsBlockFacade();
        $cmsBlockFacade->updateCmsBlockStoreRelation($expectedResult);

        // Act
        $actualResult = $cmsBlockFacade
            ->getCmsBlockStoreRelation($productAbstractRelationRequest);

        // Assert
        $actualResult->setStores(new ArrayObject());

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @dataProvider relationUpdate
     *
     * @reutrn void
     *
     * @param int[] $originalRelation
     * @param int[] $modifiedRelation
     *
     * @return void
     */
    public function testUpdateCmsBlockStoreRelation(array $originalRelation, array $modifiedRelation)
    {
        // Assign
        $idCmsBlock = 1;
        $cmsBlockRelationRequest = (new StoreRelationTransfer())
            ->setIdEntity($idCmsBlock);
        $originalRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($idCmsBlock)
            ->setIdStores($originalRelation);
        $modifiedRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($idCmsBlock)
            ->setIdStores($modifiedRelation);
        $cmsBlockFacade = $this->createCmsBlockFacade();
        $cmsBlockFacade->updateCmsBlockStoreRelation($originalRelationTransfer);

        // Act
        $beforeSaveIdStores = $cmsBlockFacade
            ->getCmsBlockStoreRelation($cmsBlockRelationRequest)
            ->getIdStores();
        $cmsBlockFacade
            ->updateCmsBlockStoreRelation($modifiedRelationTransfer);
        $afterSaveIdStores = $cmsBlockFacade
            ->getCmsBlockStoreRelation($cmsBlockRelationRequest)
            ->getIdStores();

        // Assert
        sort($beforeSaveIdStores);
        sort($afterSaveIdStores);
        $this->assertEquals($originalRelation, $beforeSaveIdStores);
        $this->assertEquals($modifiedRelation, $afterSaveIdStores);
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
     * @return \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface
     */
    protected function createCmsBlockFacade()
    {
        return $this->tester->getLocator()->cmsBlock()->facade();
    }
}
