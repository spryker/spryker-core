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
    public function testConvertOneCmsGlossaryShortCodeToHtml(): void
    {
        $inputString = $this->tester->getOneShortCodeInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);
        $this->runConvertCmsGlossaryShortCodeToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsGlossaryShortCodesToHtml(): void
    {
        $inputString = $this->tester->getTwoSameShortCodesInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);
        $this->runConvertCmsGlossaryShortCodeToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsGlossaryShortCodesToHtml(): void
    {
        $inputString = $this->tester->getTwoDifferentShortCodeInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $this->runConvertCmsGlossaryShortCodeToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsGlossaryShortCodeToHtmlWithoutElements(): void
    {
        $inputString = $this->tester->getStringWithoutShortCodesAndWidgets();
        $expectedResult = $this->tester->getStringWithoutShortCodesAndWidgets();
        $this->runConvertCmsGlossaryShortCodeToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsGlossaryHtmlToShortCode(): void
    {
        $inputString = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneShortCodeInString($this->bannerContentTransfer);
        $this->runConvertCmsGlossaryHtmlToShortCode($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsGlossaryHtmlToShortCode(): void
    {
        $inputString = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameShortCodesInString($this->bannerContentTransfer);
        $this->runConvertCmsGlossaryHtmlToShortCode($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsGlossaryHtmlToShortCode(): void
    {
        $inputString = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentShortCodeInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $this->runConvertCmsGlossaryHtmlToShortCode($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsGlossaryHtmlToShortCodeWithoutElements(): void
    {
        $inputString = $this->tester->getStringWithoutShortCodesAndWidgets();
        $expectedResult = $this->tester->getStringWithoutShortCodesAndWidgets();
        $this->runConvertCmsGlossaryHtmlToShortCode($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsBlockGlossaryShortCodeToHtml(): void
    {
        $inputString = $this->tester->getOneShortCodeInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);
        $this->runConvertCmsBlockGlossaryShortCodeToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsBlockGlossaryShortCodesToHtml(): void
    {
        $inputString = $this->tester->getTwoSameShortCodesInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);
        $this->runConvertCmsBlockGlossaryShortCodeToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsBlockGlossaryShortCodesToHtml(): void
    {
        $inputString = $this->tester->getTwoDifferentShortCodeInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $this->runConvertCmsBlockGlossaryShortCodeToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsBlockGlossaryShortCodeToHtmlWithoutElements(): void
    {
        $inputString = $this->tester->getStringWithoutShortCodesAndWidgets();
        $expectedResult = $this->tester->getStringWithoutShortCodesAndWidgets();
        $this->runConvertCmsBlockGlossaryShortCodeToHtml($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsBlockGlossaryHtmlToShortCode(): void
    {
        $inputString = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneShortCodeInString($this->bannerContentTransfer);
        $this->runConvertCmsBlockGlossaryHtmlToShortCode($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsBlockGlossaryHtmlToShortCodes(): void
    {
        $inputString = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameShortCodesInString($this->bannerContentTransfer);
        $this->runConvertCmsBlockGlossaryHtmlToShortCode($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsBlockGlossaryHtmlToShortCodes(): void
    {
        $inputString = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentShortCodeInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $this->runConvertCmsBlockGlossaryHtmlToShortCode($inputString, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsBlockGlossaryHtmlToShortCodeWithoutElements(): void
    {
        $inputString = $this->tester->getStringWithoutShortCodesAndWidgets();
        $expectedResult = $this->tester->getStringWithoutShortCodesAndWidgets();
        $this->runConvertCmsBlockGlossaryHtmlToShortCode($inputString, $expectedResult);
    }

    /**
     * @param string $inputString
     * @param string $expectedResult
     *
     * @return void
     */
    protected function runConvertCmsGlossaryShortCodeToHtml(string $inputString, string $expectedResult): void
    {
        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer($inputString);
        $contentGuiFacade = $this->getFacade();
        $cmsGlossaryTransfer = $contentGuiFacade->convertCmsGlossaryShortCodeToHtml($cmsGlossaryTransfer);
        $this->checkCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @param string $inputString
     * @param string $expectedResult
     *
     * @return void
     */
    protected function runConvertCmsGlossaryHtmlToShortCode(string $inputString, string $expectedResult): void
    {
        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer($inputString);
        $contentGuiFacade = $this->getFacade();
        $cmsGlossaryTransfer = $contentGuiFacade->convertCmsGlossaryHtmlToShortCode($cmsGlossaryTransfer);
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
    protected function runConvertCmsBlockGlossaryShortCodeToHtml(string $inputString, string $expectedResult): void
    {
        $cmsBlockGlossaryTransfer = $this->createCmsBlockGlossaryTransfer($inputString);
        $contentGuiFacade = $this->getFacade();
        $cmsBlockGlossaryTransfer = $contentGuiFacade->convertCmsBlockGlossaryShortCodeToHtml($cmsBlockGlossaryTransfer);
        $this->checkCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @param string $inputString
     * @param string $expectedResult
     *
     * @return void
     */
    protected function runConvertCmsBlockGlossaryHtmlToShortCode(string $inputString, string $expectedResult): void
    {
        $cmsBlockGlossaryTransfer = $this->createCmsBlockGlossaryTransfer($inputString);
        $contentGuiFacade = $this->getFacade();
        $cmsBlockGlossaryTransfer = $contentGuiFacade->convertCmsBlockGlossaryHtmlToShortCode($cmsBlockGlossaryTransfer);
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
