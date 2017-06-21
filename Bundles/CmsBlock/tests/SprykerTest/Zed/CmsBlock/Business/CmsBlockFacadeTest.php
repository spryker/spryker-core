<?php

namespace SprykerTest\Zed\CmsBlock\Business;


use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;

class CmsBlockFacadeTest extends Test
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

        $cmsBlockTransfer = $this->createProductLabelFacade()
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

        $this->createProductLabelFacade()
            ->activateById($cmsBlockTransfer->getIdCmsBlock());

        $cmsBlockTransfer = $this->createProductLabelFacade()
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

        $this->createProductLabelFacade()
            ->deactivateById($cmsBlockTransfer->getIdCmsBlock());

        $cmsBlockTransfer = $this->createProductLabelFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        $this->assertFalse($cmsBlockTransfer->getIsActive());
    }

    /**
     * @return void
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        $cmsBlockTransfer = $this->tester->haveCmsBlock();
        $cmsBlockTransfer->setName('Test name');

        $this->createProductLabelFacade()
            ->updateCmsBlock($cmsBlockTransfer);

        $cmsBlockTransfer = $this->createProductLabelFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        $this->assertEquals('Test name', $cmsBlockTransfer->getName());

    }

    /**
     * @return void
     */
    public function createCmsBlock()
    {
        $cmsBlockTransfer = new CmsBlockTransfer();
        $cmsBlockTransfer->setName('Test name');

        $this->createProductLabelFacade()
            ->createCmsBlock($cmsBlockTransfer);

        $this->assertNotEmpty($cmsBlockTransfer->getIdCmsBlock());
    }

    /**
     * @return void
     */
    public function findGlossaryPlaceholders()
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

        $this->createProductLabelFacade()
            ->saveGlossary($glossary);

        $glossary = $this->createProductLabelFacade()
            ->findGlossaryPlaceholders($cmsBlockTransfer->getIdCmsBlock());

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

        $this->createProductLabelFacade()
            ->saveGlossary($glossary);

        $glossary = $this->createProductLabelFacade()
            ->findGlossaryPlaceholders($cmsBlockTransfer->getIdCmsBlock());

        $this->assertNotEmpty($glossary);
    }

    /**
     * @return void
     */
    public function testCreateTemplate()
    {
        $this->createProductLabelFacade()
            ->createTemplate('test name', 'test path');

        $cmsBlockTemplateTransfer = $this->createProductLabelFacade()
            ->findTemplate('test path');

        $this->assertNotEmpty($cmsBlockTemplateTransfer);
    }

    /**
     * @return void
     */
    public function testFindTemplate()
    {
        $this->createProductLabelFacade()
            ->createTemplate('test name', 'test path');

        $cmsBlockTemplateTransfer = $this->createProductLabelFacade()
            ->findTemplate('test path');

        $this->assertEquals('test name',$cmsBlockTemplateTransfer->getTemplateName());
    }


    /**
     * @return \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface
     */
    protected function createProductLabelFacade()
    {
        return $this->tester->getLocator()->cmsBlock()->facade();
    }

}