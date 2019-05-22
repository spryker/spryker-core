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
use SprykerTest\Zed\ContentGui\Plugin\ContentBannerContentGuiEditorPluginMock;
use SprykerTest\Zed\ContentGui\Plugin\ContentProductContentGuiEditorPluginMock;

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
            new ContentBannerContentGuiEditorPluginMock(),
            new ContentProductContentGuiEditorPluginMock(),
        ]);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsGlossaryShortCodeToHtml(): void
    {
        // Arrange
        $inputString = $this->tester->getOneShortCodeInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryShortCodeToHtml($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsGlossaryShortCodesToHtml(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoSameShortCodesInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryShortCodeToHtml($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsGlossaryShortCodesToHtml(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoDifferentShortCodeInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryShortCodeToHtml($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsGlossaryShortCodeToHtmlWithoutElements(): void
    {
        // Arrange
        $inputString = $this->tester->getStringWithoutShortCodesAndWidgets();
        $expectedResult = $this->tester->getStringWithoutShortCodesAndWidgets();

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryShortCodeToHtml($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsGlossaryHtmlToShortCode(): void
    {
        // Arrange
        $inputString = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneShortCodeInString($this->bannerContentTransfer, true);

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryHtmlToShortCode($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsGlossaryHtmlToShortCode(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameShortCodesInString($this->bannerContentTransfer, true);

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryHtmlToShortCode($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsGlossaryHtmlToShortCode(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentShortCodeInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer,
            true
        );

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryHtmlToShortCode($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsGlossaryHtmlToShortCodeWithoutElements(): void
    {
        // Arrange
        $inputString = $this->tester->getStringWithoutShortCodesAndWidgets();
        $expectedResult = $this->tester->getStringWithoutShortCodesAndWidgets();

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryHtmlToShortCode($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsBlockGlossaryShortCodeToHtml(): void
    {
        // Arrange
        $inputString = $this->tester->getOneShortCodeInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryShortCodeToHtml($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsBlockGlossaryShortCodesToHtml(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoSameShortCodesInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryShortCodeToHtml($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsBlockGlossaryShortCodesToHtml(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoDifferentShortCodeInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryShortCodeToHtml($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsBlockGlossaryShortCodeToHtmlWithoutElements(): void
    {
        // Arrange
        $inputString = $this->tester->getStringWithoutShortCodesAndWidgets();
        $expectedResult = $this->tester->getStringWithoutShortCodesAndWidgets();

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryShortCodeToHtml($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsBlockGlossaryHtmlToShortCode(): void
    {
        // Arrange
        $inputString = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneShortCodeInString($this->bannerContentTransfer, true);

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryHtmlToShortCode($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsBlockGlossaryHtmlToShortCodes(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameShortCodesInString($this->bannerContentTransfer, true);

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryHtmlToShortCode($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsBlockGlossaryHtmlToShortCodes(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentShortCodeInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer,
            true
        );

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryHtmlToShortCode($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsBlockGlossaryHtmlToShortCodeWithoutElements(): void
    {
        // Arrange
        $inputString = $this->tester->getStringWithoutShortCodesAndWidgets();
        $expectedResult = $this->tester->getStringWithoutShortCodesAndWidgets();

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryHtmlToShortCode($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @param string $inputString
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    protected function runConvertCmsGlossaryShortCodeToHtml(string $inputString): CmsGlossaryTransfer
    {
        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer($inputString);

        return $this->tester->getFacade()
            ->convertCmsGlossaryShortCodeToHtml($cmsGlossaryTransfer);
    }

    /**
     * @param string $inputString
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    protected function runConvertCmsGlossaryHtmlToShortCode(string $inputString): CmsGlossaryTransfer
    {
        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer($inputString);

        return $this->tester->getFacade()
            ->convertCmsGlossaryHtmlToShortCode($cmsGlossaryTransfer);
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
    protected function assertCmsGlossaryResult(CmsGlossaryTransfer $cmsGlossaryTransfer, string $expectedResult): void
    {
        // Assert
        $this->assertInstanceOf(CmsGlossaryTransfer::class, $cmsGlossaryTransfer);

        foreach ($cmsGlossaryTransfer->getGlossaryAttributes() as $cmsGlossaryAttributesTransfer) {
            foreach ($cmsGlossaryAttributesTransfer->getTranslations() as $cmsPlaceholderTranslationTransfer) {
                $translation = $cmsPlaceholderTranslationTransfer->getTranslation();
                $this->assertIsString($translation);
                $this->assertEquals($expectedResult, $translation);
            }
        }
    }

    /**
     * @param string $inputString
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    protected function runConvertCmsBlockGlossaryShortCodeToHtml(string $inputString): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryTransfer = $this->createCmsBlockGlossaryTransfer($inputString);

        return $this->tester->getFacade()
            ->convertCmsBlockGlossaryShortCodeToHtml($cmsBlockGlossaryTransfer);
    }

    /**
     * @param string $inputString
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    protected function runConvertCmsBlockGlossaryHtmlToShortCode(string $inputString): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryTransfer = $this->createCmsBlockGlossaryTransfer($inputString);

        return $this->tester->getFacade()
            ->convertCmsBlockGlossaryHtmlToShortCode($cmsBlockGlossaryTransfer);
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
    protected function assertCmsBlockGlossaryResult(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer, string $expectedResult): void
    {
        $this->assertInstanceOf(CmsBlockGlossaryTransfer::class, $cmsBlockGlossaryTransfer);

        foreach ($cmsBlockGlossaryTransfer->getGlossaryPlaceholders() as $cmsBlockGlossaryPlaceholderTransfer) {
            foreach ($cmsBlockGlossaryPlaceholderTransfer->getTranslations() as $cmsBlockGlossaryPlaceholderTranslationTransfer) {
                $translation = $cmsBlockGlossaryPlaceholderTranslationTransfer->getTranslation();
                $this->assertIsString($translation);
                $this->assertEquals($expectedResult, $translation);
            }
        }
    }
}
