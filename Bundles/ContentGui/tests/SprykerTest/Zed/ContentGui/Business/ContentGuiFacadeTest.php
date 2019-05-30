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
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(ContentGuiDependencyProvider::PLUGINS_CONTENT_EDITOR, [
            new ContentBannerContentGuiEditorPluginMock(),
            new ContentProductContentGuiEditorPluginMock(),
        ]);
    }

    /**
     * @dataProvider getContent
     *
     * @param string $inputContentMethod
     * @param string $expectedContentMethod
     *
     * @return void
     */
    public function testCmsBlockGlossaryHtmlToTwigExpression(string $inputContentMethod, string $expectedContentMethod): void
    {
        // Arrange
        $inputString = $this->tester->{$inputContentMethod}();
        $expectedResult = $this->tester->{$expectedContentMethod}(true);

        // It's necessary because DOMDocument returns valid HTML after converting
        if ($expectedContentMethod === 'getTwoSameTwigExpressionsWithInvalidHtml') {
            $expectedResult = str_replace('<p>', '', $expectedResult);
        }

        $cmsBlockGlossaryTransfer = $this->createCmsBlockGlossaryTransfer($inputString);

        // Act
        $cmsBlockGlossaryTransfer = $this->tester->getFacade()->convertCmsBlockGlossaryHtmlToTwigExpression($cmsBlockGlossaryTransfer);

        // Assert
        $this->assertCmsBlockGlossaryResults($expectedResult, $cmsBlockGlossaryTransfer);
    }

    /**
     * @dataProvider getContent
     *
     * @param string $expectedContentMethod
     * @param string $inputContentMethod
     *
     * @return void
     */
    public function testCmsBlockGlossaryTwigExpressionToHtml(string $expectedContentMethod, string $inputContentMethod): void
    {
        // This method only for test conversion html to twig expression
        if ($expectedContentMethod === 'getInvalidHtmlWidget') {
            return;
        }

        // Arrange
        $inputString = $this->tester->{$inputContentMethod}();
        $expectedResult = $this->tester->{$expectedContentMethod}(true);
        $cmsBlockGlossaryTransfer = $this->createCmsBlockGlossaryTransfer($inputString);

        // Act
        $cmsBlockGlossaryTransfer = $this->tester->getFacade()->convertCmsBlockGlossaryTwigExpressionToHtml($cmsBlockGlossaryTransfer);

        // Assert
        $this->assertCmsBlockGlossaryResults($expectedResult, $cmsBlockGlossaryTransfer);
    }

    /**
     * @dataProvider getContent
     *
     * @param string $inputContentMethod
     * @param string $expectedContentMethod
     *
     * @return void
     */
    public function testCmsGlossaryHtmlToTwigExpression(string $inputContentMethod, string $expectedContentMethod): void
    {
        // Arrange
        $inputString = $this->tester->{$inputContentMethod}();
        $expectedResult = $this->tester->{$expectedContentMethod}(true);

        // It's necessary because DOMDocument returns valid HTML after converting
        if ($expectedContentMethod === 'getTwoSameTwigExpressionsWithInvalidHtml') {
            $expectedResult = str_replace('<p>', '', $expectedResult);
        }

        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer($inputString);

        // Act
        $cmsGlossaryTransfer = $this->tester->getFacade()->convertCmsGlossaryHtmlToTwigExpression($cmsGlossaryTransfer);

        // Assert
        $this->assertCmsGlossaryResults($expectedResult, $cmsGlossaryTransfer);
    }

    /**
     * @dataProvider getContent
     *
     * @param string $expectedContentMethod
     * @param string $inputContentMethod
     *
     * @return void
     */
    public function testCmsGlossaryTwigExpressionToHtml(string $expectedContentMethod, string $inputContentMethod): void
    {
        // This method only for test conversion html to twig expression
        if ($expectedContentMethod === 'getInvalidHtmlWidget') {
            return;
        }

        // Arrange
        $inputString = $this->tester->{$inputContentMethod}();
        $expectedResult = $this->tester->{$expectedContentMethod}(true);
        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer($inputString);

        // Act
        $cmsGlossaryTransfer = $this->tester->getFacade()->convertCmsGlossaryTwigExpressionToHtml($cmsGlossaryTransfer);

        // Assert
        $this->assertCmsGlossaryResults($expectedResult, $cmsGlossaryTransfer);
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        return [
            'one element without html' => ['getOneHtmlWidget', 'getOneTwigExpression'],
            'two of the same elements without html' => ['getTwoSameHtmlWidgets', 'getTwoSameTwigExpressions'],
            'two different elements without html' => ['getTwoDifferentHtmlWidgets', 'getTwoDifferentTwigExpression'],
            'empty string' => ['getEmptyString', 'getEmptyString'],
            'wrong html' => ['getWrongHtml', 'getWrongHtml'],
            'one element with invalid content item' => ['getInvalidContentItemTwigExpression', 'getInvalidContentItemTwigExpression'],
            'two of the same elements with invalid HTML' => ['getTwoSameHtmlWidgetsWithInvalidHtml', 'getTwoSameTwigExpressionsWithInvalidHtml'],
            'just text with part of twig expression' => ['getStringWithPartOfTwigExpression', 'getStringWithPartOfTwigExpression'],
            'invalid html widget' => ['getInvalidHtmlWidget', 'getOneTwigExpression'],
        ];
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
     * @param string $expectedResult
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return void
     */
    protected function assertCmsBlockGlossaryResults(string $expectedResult, CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): void
    {
        // Assert
        $translation = $cmsBlockGlossaryTransfer->getGlossaryPlaceholders()->offsetGet(0)->getTranslations()->offsetGet(0)->getTranslation();
        $this->assertTrue(isset($translation));

        foreach ($cmsBlockGlossaryTransfer->getGlossaryPlaceholders() as $cmsBlockGlossaryPlaceholderTransfer) {
            foreach ($cmsBlockGlossaryPlaceholderTransfer->getTranslations() as $cmsPlaceholderTranslationTransfer) {
                $translation = $cmsPlaceholderTranslationTransfer->getTranslation();
                $this->assertIsString($translation);
                $this->assertEquals($expectedResult, $translation);
            }
        }
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
     * @param string $expectedResult
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return void
     */
    protected function assertCmsGlossaryResults(string $expectedResult, CmsGlossaryTransfer $cmsGlossaryTransfer): void
    {
        // Assert
        $translation = $cmsGlossaryTransfer->getGlossaryAttributes()->offsetGet(0)->getTranslations()->offsetGet(0)->getTranslation();
        $this->assertTrue(isset($translation));

        foreach ($cmsGlossaryTransfer->getGlossaryAttributes() as $cmsGlossaryAttributesTransfer) {
            foreach ($cmsGlossaryAttributesTransfer->getTranslations() as $cmsPlaceholderTranslationTransfer) {
                $translation = $cmsPlaceholderTranslationTransfer->getTranslation();
                $this->assertIsString($translation);
                $this->assertEquals($expectedResult, $translation);
            }
        }
    }
}
