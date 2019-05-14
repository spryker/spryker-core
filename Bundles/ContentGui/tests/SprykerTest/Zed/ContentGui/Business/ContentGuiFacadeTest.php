<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentGui\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Spryker\Zed\ContentGui\ContentGuiDependencyProvider;
use SprykerTest\Zed\ContentGui\Plugin\ContentBannerContentGuiEditorPlugin;
use SprykerTest\Zed\ContentGui\Plugin\ContentProductContentGuiEditorPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ContentGui
 * @group Business
 * @group Facade
 * @group ContentGuiFacadeTest
 * Add your own group annotations below this line
 */
class ContentGuiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ContentGui\ContentGuiBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\ContentTransfer
     */
    protected $bannerContentTransfer;

    /**
     * @var \Generated\Shared\Transfer\ContentTransfer
     */
    protected $abstractProductListContentTransfer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->bannerContentTransfer = $this->tester->createBannerContentItem();
        $this->abstractProductListContentTransfer = $this->tester->createAbstractProductListContentItem();

        $this->tester->setDependency(ContentGuiDependencyProvider::PLUGINS_CONTENT_EDITOR, [
            new ContentBannerContentGuiEditorPlugin(),
            new ContentProductContentGuiEditorPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsGlossaryTwigFunctionToHtml(): void
    {
        $inputString = $this->tester->getOneTwigFunctionInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);
        $this->runConvertCmsGlossaryTwigFunctionToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsGlossaryTwigFunctionsToHtml(): void
    {
        $inputString = $this->tester->getTwoSameTwigFunctionsInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);
        $this->runConvertCmsGlossaryTwigFunctionToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsGlossaryTwigFunctionsToHtml(): void
    {
        $inputString = $this->tester->getTwoDifferentTwigFunctionInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $this->runConvertCmsGlossaryTwigFunctionToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsGlossaryTwigFunctionToHtmlWithoutElements(): void
    {
        $inputString = $this->tester->getStringWithoutTwigFunctionsAndWidgets();
        $expectedResult = $this->tester->getStringWithoutTwigFunctionsAndWidgets();
        $this->runConvertCmsGlossaryTwigFunctionToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsGlossaryHtmlToTwigFunction(): void
    {
        $inputString = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneTwigFunctionInString($this->bannerContentTransfer);
        $this->runConvertCmsGlossaryHtmlToTwigFunction($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsGlossaryHtmlToTwigFunction(): void
    {
        $inputString = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameTwigFunctionsInString($this->bannerContentTransfer);
        $this->runConvertCmsGlossaryHtmlToTwigFunction($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsGlossaryHtmlToTwigFunction(): void
    {
        $inputString = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentTwigFunctionInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $this->runConvertCmsGlossaryHtmlToTwigFunction($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsGlossaryHtmlToTwigFunctionWithoutElements(): void
    {
        $inputString = $this->tester->getStringWithoutTwigFunctionsAndWidgets();
        $expectedResult = $this->tester->getStringWithoutTwigFunctionsAndWidgets();
        $this->runConvertCmsGlossaryHtmlToTwigFunction($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsBlockGlossaryTwigFunctionToHtml(): void
    {
        $inputString = $this->tester->getOneTwigFunctionInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);
        $this->runConvertCmsBlockGlossaryTwigFunctionToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsBlockGlossaryTwigFunctionsToHtml(): void
    {
        $inputString = $this->tester->getTwoSameTwigFunctionsInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);
        $this->runConvertCmsBlockGlossaryTwigFunctionToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsBlockGlossaryTwigFunctionsToHtml(): void
    {
        $inputString = $this->tester->getTwoDifferentTwigFunctionInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $this->runConvertCmsBlockGlossaryTwigFunctionToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsBlockGlossaryTwigFunctionToHtmlWithoutElements(): void
    {
        $inputString = $this->tester->getStringWithoutTwigFunctionsAndWidgets();
        $expectedResult = $this->tester->getStringWithoutTwigFunctionsAndWidgets();
        $this->runConvertCmsBlockGlossaryTwigFunctionToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsBlockGlossaryHtmlToTwigFunction(): void
    {
        $inputString = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneTwigFunctionInString($this->bannerContentTransfer);
        $this->runConvertCmsBlockGlossaryHtmlToTwigFunction($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsBlockGlossaryHtmlToTwigFunctions(): void
    {
        $inputString = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameTwigFunctionsInString($this->bannerContentTransfer);
        $this->runConvertCmsBlockGlossaryHtmlToTwigFunction($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsBlockGlossaryHtmlToTwigFunctions(): void
    {
        $inputString = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentTwigFunctionInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $this->runConvertCmsBlockGlossaryHtmlToTwigFunction($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsBlockGlossaryHtmlToTwigFunctionWithoutElements(): void
    {
        $inputString = $this->tester->getStringWithoutTwigFunctionsAndWidgets();
        $expectedResult = $this->tester->getStringWithoutTwigFunctionsAndWidgets();
        $this->runConvertCmsBlockGlossaryHtmlToTwigFunction($inputString, $expectedResult);
    }

    /**
     * @param string $inputString
     * @param string $expectedResult
     *
     * @return void
     */
    protected function runConvertCmsGlossaryTwigFunctionToHtml(string $inputString, string $expectedResult): void
    {
        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer($inputString);
        $contentGuiFacade = $this->getFacade();
        $cmsGlossaryTransfer = $contentGuiFacade->convertCmsGlossaryTwigFunctionToHtml($cmsGlossaryTransfer);
        $this->checkCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @param string $inputString
     * @param string $expectedResult
     *
     * @return void
     */
    protected function runConvertCmsGlossaryHtmlToTwigFunction(string $inputString, string $expectedResult): void
    {
        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer($inputString);
        $contentGuiFacade = $this->getFacade();
        $cmsGlossaryTransfer = $contentGuiFacade->convertCmsGlossaryHtmlToTwigFunction($cmsGlossaryTransfer);
        $this->checkCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @param string $actualString
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    protected function createCmsGlossaryTransfer(string $actualString): CmsGlossaryTransfer
    {
        $cmsPlaceholderTranslationTransfer = (new CmsPlaceholderTranslationTransfer())
            ->setTranslation($actualString);

        $cmsGlossaryAttributesTransfer = (new CmsGlossaryAttributesTransfer())
            ->addTranslation($cmsPlaceholderTranslationTransfer);

        return (new CmsGlossaryTransfer())
            ->addGlossaryAttribute($cmsGlossaryAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     * @param string $expectedResult
     *
     * @return void
     */
    protected function checkCmsGlossaryResult(CmsGlossaryTransfer $cmsGlossaryTransfer, string $expectedResult): void
    {
        $this->assertInstanceOf(CmsGlossaryTransfer::class, $cmsGlossaryTransfer);

        foreach ($cmsGlossaryTransfer->getGlossaryAttributes() as $cmsGlossaryAttributesTransfer) {
            foreach ($cmsGlossaryAttributesTransfer->getTranslations() as $cmsPlaceholderTranslationTransfer) {
                $translation = str_replace("\n", '', $cmsPlaceholderTranslationTransfer->getTranslation());
                $this->assertIsString($translation);
                $this->assertEquals($expectedResult, $translation);
            }
        }
    }

    /**
     * @param string $inputString
     * @param string $expectedResult
     *
     * @return void
     */
    protected function runConvertCmsBlockGlossaryTwigFunctionToHtml(string $inputString, string $expectedResult): void
    {
        $cmsBlockGlossaryTransfer = $this->createCmsBlockGlossaryTransfer($inputString);
        $contentGuiFacade = $this->getFacade();
        $cmsBlockGlossaryTransfer = $contentGuiFacade->convertCmsBlockGlossaryTwigFunctionToHtml($cmsBlockGlossaryTransfer);
        $this->checkCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @param string $inputString
     * @param string $expectedResult
     *
     * @return void
     */
    protected function runConvertCmsBlockGlossaryHtmlToTwigFunction(string $inputString, string $expectedResult): void
    {
        $cmsBlockGlossaryTransfer = $this->createCmsBlockGlossaryTransfer($inputString);
        $contentGuiFacade = $this->getFacade();
        $cmsBlockGlossaryTransfer = $contentGuiFacade->convertCmsBlockGlossaryHtmlToTwigFunction($cmsBlockGlossaryTransfer);
        $this->checkCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @param string $inputString
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    protected function createCmsBlockGlossaryTransfer(string $inputString): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryPlaceholderTranslationTransfer = (new CmsBlockGlossaryPlaceholderTranslationTransfer())
            ->setTranslation($inputString);

        $cmsBlockGlossaryPlaceholderTransfer = (new CmsBlockGlossaryPlaceholderTransfer())
            ->addTranslation($cmsBlockGlossaryPlaceholderTranslationTransfer);

        return (new CmsBlockGlossaryTransfer())
            ->addGlossaryPlaceholder($cmsBlockGlossaryPlaceholderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     * @param string $expectedResult
     *
     * @return void
     */
    protected function checkCmsBlockGlossaryResult(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer, string $expectedResult): void
    {
        $this->assertInstanceOf(CmsBlockGlossaryTransfer::class, $cmsBlockGlossaryTransfer);

        foreach ($cmsBlockGlossaryTransfer->getGlossaryPlaceholders() as $cmsBlockGlossaryPlaceholderTransfer) {
            foreach ($cmsBlockGlossaryPlaceholderTransfer->getTranslations() as $cmsBlockGlossaryPlaceholderTranslationTransfer) {
                $translation = str_replace("\n", '', $cmsBlockGlossaryPlaceholderTranslationTransfer->getTranslation());
                $this->assertIsString($translation);
                $this->assertEquals($expectedResult, $translation);
            }
        }
    }

    /**
     * @return \Spryker\Zed\ContentGui\Business\ContentGuiFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
