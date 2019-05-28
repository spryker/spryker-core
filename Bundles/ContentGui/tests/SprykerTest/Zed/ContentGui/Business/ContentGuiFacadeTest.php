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
    public function testConvertOneCmsGlossaryTwigExpressionToHtml(): void
    {
        // Arrange
        $inputString = $this->tester->getOneTwigExpressionInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryTwigExpressionToHtml($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsGlossaryTwigExpressionsToHtml(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoSameTwigExpressionsInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryTwigExpressionToHtml($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsGlossaryTwigExpressionsToHtml(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoDifferentTwigExpressionInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryTwigExpressionToHtml($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsGlossaryTwigExpressionToHtmlWithoutElements(): void
    {
        // Arrange
        $inputString = $this->tester->getStringWithoutTwigExpressionsAndWidgets();
        $expectedResult = $this->tester->getStringWithoutTwigExpressionsAndWidgets();

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryTwigExpressionToHtml($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsGlossaryHtmlToTwigExpression(): void
    {
        // Arrange
        $inputString = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneTwigExpressionInString($this->bannerContentTransfer, true);

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryHtmlToTwigExpression($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsGlossaryHtmlToTwigExpression(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameTwigExpressionsInString($this->bannerContentTransfer, true);

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryHtmlToTwigExpression($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsGlossaryHtmlToTwigExpression(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentTwigExpressionInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer,
            true
        );

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryHtmlToTwigExpression($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsGlossaryHtmlToTwigExpressionWithoutElements(): void
    {
        // Arrange
        $inputString = $this->tester->getStringWithoutTwigExpressionsAndWidgets();
        $expectedResult = $this->tester->getStringWithoutTwigExpressionsAndWidgets();

        // Act
        $cmsGlossaryTransfer = $this->runConvertCmsGlossaryHtmlToTwigExpression($inputString);

        // Assert
        $this->assertCmsGlossaryResult($cmsGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsBlockGlossaryTwigExpressionToHtml(): void
    {
        // Arrange
        $inputString = $this->tester->getOneTwigExpressionInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryTwigExpressionToHtml($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsBlockGlossaryTwigExpressionsToHtml(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoSameTwigExpressionsInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryTwigExpressionToHtml($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsBlockGlossaryTwigExpressionsToHtml(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoDifferentTwigExpressionInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryTwigExpressionToHtml($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsBlockGlossaryTwigExpressionToHtmlWithoutElements(): void
    {
        // Arrange
        $inputString = $this->tester->getStringWithoutTwigExpressionsAndWidgets();
        $expectedResult = $this->tester->getStringWithoutTwigExpressionsAndWidgets();

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryTwigExpressionToHtml($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertOneCmsBlockGlossaryHtmlToTwigExpression(): void
    {
        // Arrange
        $inputString = $this->tester->getOneHtmlWidgetInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getOneTwigExpressionInString($this->bannerContentTransfer, true);

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryHtmlToTwigExpression($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoSameCmsBlockGlossaryHtmlToTwigExpressions(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoSameHtmlWidgetsInString($this->bannerContentTransfer);
        $expectedResult = $this->tester->getTwoSameTwigExpressionsInString($this->bannerContentTransfer, true);

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryHtmlToTwigExpression($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertTwoDifferentCmsBlockGlossaryHtmlToTwigExpressions(): void
    {
        // Arrange
        $inputString = $this->tester->getTwoDifferentHtmlWidgetsInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer
        );
        $expectedResult = $this->tester->getTwoDifferentTwigExpressionInString(
            $this->bannerContentTransfer,
            $this->abstractProductListContentTransfer,
            true
        );

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryHtmlToTwigExpression($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @return void
     */
    public function testConvertCmsBlockGlossaryHtmlToTwigExpressionWithoutElements(): void
    {
        // Arrange
        $inputString = $this->tester->getStringWithoutTwigExpressionsAndWidgets();
        $expectedResult = $this->tester->getStringWithoutTwigExpressionsAndWidgets();

        // Act
        $cmsBlockGlossaryTransfer = $this->runConvertCmsBlockGlossaryHtmlToTwigExpression($inputString);

        // Assert
        $this->assertCmsBlockGlossaryResult($cmsBlockGlossaryTransfer, $expectedResult);
    }

    /**
     * @param string $inputString
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    protected function runConvertCmsGlossaryTwigExpressionToHtml(string $inputString): CmsGlossaryTransfer
    {
        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer($inputString);

        return $this->tester->getFacade()
            ->convertCmsGlossaryTwigExpressionToHtml($cmsGlossaryTransfer);
    }

    /**
     * @param string $inputString
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    protected function runConvertCmsGlossaryHtmlToTwigExpression(string $inputString): CmsGlossaryTransfer
    {
        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer($inputString);

        return $this->tester->getFacade()
            ->convertCmsGlossaryHtmlToTwigExpression($cmsGlossaryTransfer);
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
    protected function runConvertCmsBlockGlossaryTwigExpressionToHtml(string $inputString): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryTransfer = $this->createCmsBlockGlossaryTransfer($inputString);

        return $this->tester->getFacade()
            ->convertCmsBlockGlossaryTwigExpressionToHtml($cmsBlockGlossaryTransfer);
    }

    /**
     * @param string $inputString
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    protected function runConvertCmsBlockGlossaryHtmlToTwigExpression(string $inputString): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryTransfer = $this->createCmsBlockGlossaryTransfer($inputString);

        return $this->tester->getFacade()
            ->convertCmsBlockGlossaryHtmlToTwigExpression($cmsBlockGlossaryTransfer);
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
