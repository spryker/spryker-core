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
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\ContentGui\Business\ContentGuiBusinessFactory;
use Spryker\Zed\ContentGui\Business\ContentGuiFacade;
use Spryker\Zed\ContentGui\Business\ContentGuiFacadeInterface;
use Spryker\Zed\ContentGui\ContentGuiDependencyProvider;
use SprykerTest\Zed\ContentGui\ContentGuiConfigTest;
use SprykerTest\Zed\ContentGui\Plugin\ContentBannerContentGuiEditorPluginMock;
use SprykerTest\Zed\ContentGui\Plugin\ContentProductContentGuiEditorPluginMock;

/**
 * Auto-generated group annotations
 *
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
     * @var \Spryker\Zed\ContentGui\Business\ContentGuiFacadeInterface
     */
    protected $contentGuiFacade;

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

        $this->contentGuiFacade = $this->createContentGuiFacade();

        $this->tester->createBannerContentItem();
        $this->tester->createAbstractProductListContentItem();
    }

    /**
     * @dataProvider getContentHtmlToTwigExpression
     *
     * @param string $input
     * @param string $expectedResult
     *
     * @return void
     */
    public function testCmsBlockGlossaryHtmlToTwigExpression(string $input, string $expectedResult): void
    {
        // Arrange
        $cmsBlockGlossaryTransfer = $this->createCmsBlockGlossaryTransfer($input);

        // Act
        $cmsBlockGlossaryTransfer = $this->contentGuiFacade->convertCmsBlockGlossaryHtmlToTwigExpressions($cmsBlockGlossaryTransfer);

        // Assert
        $this->assertCmsBlockGlossaryResults($expectedResult, $cmsBlockGlossaryTransfer);
    }

    /**
     * @dataProvider getContentHtmlToTwigExpression
     *
     * @param string $input
     * @param string $expectedResult
     *
     * @return void
     */
    public function testCmsGlossaryHtmlToTwigExpression(string $input, string $expectedResult): void
    {
        // Arrange
        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer($input);

        // Act
        $cmsGlossaryTransfer = $this->contentGuiFacade->convertCmsGlossaryHtmlToTwigExpressions($cmsGlossaryTransfer);

        // Assert
        $this->assertCmsGlossaryResults($expectedResult, $cmsGlossaryTransfer);
    }

    /**
     * @dataProvider getContentTwigExpressionToHtml
     *
     * @param string $input
     * @param string $expectedResult
     * @param string $localeName
     *
     * @return void
     */
    public function testCmsBlockGlossaryTwigExpressionToHtml(string $input, string $expectedResult, string $localeName = 'en_US'): void
    {
        // Arrange
        Store::getInstance()->setCurrentLocale($localeName);
        $cmsBlockGlossaryTransfer = $this->createCmsBlockGlossaryTransfer($input);

        // Act
        $cmsBlockGlossaryTransfer = $this->contentGuiFacade->convertCmsBlockGlossaryTwigExpressionsToHtml($cmsBlockGlossaryTransfer);

        // Assert
        $this->assertCmsBlockGlossaryResults($expectedResult, $cmsBlockGlossaryTransfer);
    }

    /**
     * @dataProvider getContentTwigExpressionToHtml
     *
     * @param string $input
     * @param string $expectedResult
     * @param string $localeName
     *
     * @return void
     */
    public function testCmsGlossaryTwigExpressionToHtml(string $input, string $expectedResult, string $localeName = 'en_US'): void
    {
        // Arrange
        Store::getInstance()->setCurrentLocale($localeName);
        $cmsGlossaryTransfer = $this->createCmsGlossaryTransfer($input);

        // Act
        $cmsGlossaryTransfer = $this->contentGuiFacade->convertCmsGlossaryTwigExpressionsToHtml($cmsGlossaryTransfer);

        // Assert
        $this->assertCmsGlossaryResults($expectedResult, $cmsGlossaryTransfer);
    }

    /**
     * @return array
     */
    public function getContentTwigExpressionToHtml(): array
    {
        return [
            'one element without html' => [
                "{{ content_banner('br-test', 'default') }}",

                '<span data-type="Banner" data-key="br-test" data-template="default" '
                . 'data-twig-expression="{{ content_banner(\'br-test\', \'default\') }}">'
                . '<span>Content Item Type: Banner</span>'
                . '<span>Name: Test Banner</span>'
                . '<span>Template: Default</span>'
                . '</span>',
            ],
            'two of the same elements without html' => [
                "{{ content_banner('br-test', 'default') }}{{ content_banner('br-test', 'default') }}",

                '<span data-type="Banner" data-key="br-test" data-template="default" '
                . 'data-twig-expression="{{ content_banner(\'br-test\', \'default\') }}">'
                . '<span>Content Item Type: Banner</span>'
                . '<span>Name: Test Banner</span>'
                . '<span>Template: Default</span>'
                . '</span>'
                . '<span data-type="Banner" data-key="br-test" data-template="default" '
                . 'data-twig-expression="{{ content_banner(\'br-test\', \'default\') }}">'
                . '<span>Content Item Type: Banner</span>'
                . '<span>Name: Test Banner</span>'
                . '<span>Template: Default</span>'
                . '</span>',
            ],
            'two different elements without html' => [
                "{{ content_banner('apl-test', 'top-title') }}{{ content_banner('br-test', 'default') }}",

                '<span data-type="Abstract Product List" data-key="apl-test" data-template="top-title" '
                . 'data-twig-expression="{{ content_banner(\'apl-test\', \'top-title\') }}">'
                . '<span>Content Item Type: Abstract Product List</span>'
                . '<span>Name: Test Product List</span>'
                . '<span>Template: Top Title</span>'
                . '</span>'
                . '<span data-type="Banner" data-key="br-test" data-template="default" '
                . 'data-twig-expression="{{ content_banner(\'br-test\', \'default\') }}">'
                . '<span>Content Item Type: Banner</span>'
                . '<span>Name: Test Banner</span>'
                . '<span>Template: Default</span>'
                . '</span>',
            ],
            'empty string' => [
                '',
                '',
            ],
            'wrong html' => [
                '<p></p><div>',
                '<p></p><div>',
            ],
            'two of the same elements with invalid HTML' => [
                "{{ content_banner('br-test', 'default') }}<p>{{ content_banner('br-test', 'default') }}",

                '<span data-type="Banner" data-key="br-test" data-template="default" '
                . 'data-twig-expression="{{ content_banner(\'br-test\', \'default\') }}">'
                . '<span>Content Item Type: Banner</span>'
                . '<span>Name: Test Banner</span>'
                . '<span>Template: Default</span>'
                . '</span>'
                . '<p>'
                . '<span data-type="Banner" data-key="br-test" data-template="default" '
                . 'data-twig-expression="{{ content_banner(\'br-test\', \'default\') }}">'
                . '<span>Content Item Type: Banner</span>'
                . '<span>Name: Test Banner</span>'
                . '<span>Template: Default</span>'
                . '</span>',
            ],
            'just text with part of twig expression' => [
                '{{ content_banner just text',
                '{{ content_banner just text',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getContentHtmlToTwigExpression(): array
    {
        return [
            'one element without html' => [
                '<span data-type="Banner" data-key="br-test" data-template="default" '
                . 'data-twig-expression="{{ content_banner(\'br-test\', \'default\') }}">'
                . '<span>Name: Test Banner</span>'
                . '<span>Template: Default</span>'
                . '</span>',

                "{{ content_banner('br-test', 'default') }}",
            ],
            'two of the same elements without html' => [
                '<span data-type="Banner" data-key="br-test" data-template="default" '
                . 'data-twig-expression="{{ content_banner(\'br-test\', \'default\') }}">'
                . '<span>Name: Test Banner</span>'
                . '<span>Template: Default</span>'
                . '</span>'
                . '<span data-type="Banner" data-key="br-test" data-template="default" '
                . 'data-twig-expression="{{ content_banner(\'br-test\', \'default\') }}">'
                . '<span>Name: Test Banner</span>'
                . '<span>Template: Default</span>'
                . '</span>',

                "{{ content_banner('br-test', 'default') }}{{ content_banner('br-test', 'default') }}",

            ],
            'two different elements without html' => [
                '<span data-type="Abstract Product List" data-key="apl-test" data-template="top-title" '
                . 'data-twig-expression="{{ content_banner(\'apl-test\', \'top-title\') }}">'
                . '<span>Name: Test Product List</span>'
                . '<span>Template: Top Title</span>'
                . '</span>'
                . '<span data-type="Banner" data-key="br-test" data-template="default" '
                . 'data-twig-expression="{{ content_banner(\'br-test\', \'default\') }}">'
                . '<span>Name: Test Banner</span>'
                . '<span>Template: Default</span>'
                . '</span>',

                "{{ content_banner('apl-test', 'top-title') }}{{ content_banner('br-test', 'default') }}",

            ],
            'empty string' => [
                '',
                '',
            ],
            'wrong html' => [
                '<p></p><div>',
                '<p></p><div>',
            ],
            'one element with not-existing content item' => [
                "{{ content_banner('test', 'test') }}",
                "{{ content_banner('test', 'test') }}",
            ],
            'two of the same elements with invalid HTML' => [
                '<span data-type="Banner" data-key="br-test" data-template="default" '
                . 'data-twig-expression="{{ content_banner(\'br-test\', \'default\') }}">'
                . '<span>Name: Test Banner</span>'
                . '<span>Template: Default</span>'
                . '</span>'
                . '<p>'
                . '<span data-type="Banner" data-key="br-test" data-template="default" '
                . 'data-twig-expression="{{ content_banner(\'br-test\', \'default\') }}">'
                . '<span>Name: Test Banner</span>'
                . '<span>Template: Default</span>'
                . '</span>',

                "{{ content_banner('br-test', 'default') }}{{ content_banner('br-test', 'default') }}",
            ],
            'just text with part of twig expression' => [
                '{{ content_banner just text',
                '{{ content_banner just text',
            ],
            'invalid html widget' => [
                '<span data-type="Abstract Product List" data-key="apl-test" data-template="top-title" '
                . 'data-twig-expression="{{ content_banner(\'apl-test\', \'top-title\') }}">',

                "{{ content_banner('apl-test', 'top-title') }}",
            ],
        ];
    }

    /**
     * @param string $input
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    protected function createCmsBlockGlossaryTransfer(string $input): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryPlaceholderTranslationTransfer = (new CmsBlockGlossaryPlaceholderTranslationTransfer())
            ->setTranslation($input);

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
        $this->assertNotEmpty($cmsBlockGlossaryTransfer->getGlossaryPlaceholders());
        $this->assertNotEmpty($cmsBlockGlossaryTransfer->getGlossaryPlaceholders()->offsetGet(0)->getTranslations());
        $translation = $cmsBlockGlossaryTransfer->getGlossaryPlaceholders()->offsetGet(0)->getTranslations()->offsetGet(0)->getTranslation();
        $this->assertIsString($translation);
        $this->assertEquals($expectedResult, $translation);
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
    public function assertCmsGlossaryResults(string $expectedResult, CmsGlossaryTransfer $cmsGlossaryTransfer): void
    {
        // Assert
        $this->assertNotEmpty($cmsGlossaryTransfer->getGlossaryAttributes());
        $this->assertNotEmpty($cmsGlossaryTransfer->getGlossaryAttributes()->offsetGet(0)->getTranslations());
        $translation = $cmsGlossaryTransfer->getGlossaryAttributes()->offsetGet(0)->getTranslations()->offsetGet(0)->getTranslation();
        $this->assertIsString($translation);
        $this->assertEquals($expectedResult, $translation);
    }

    /**
     * @return \Spryker\Zed\ContentGui\Business\ContentGuiFacadeInterface
     */
    protected function createContentGuiFacade(): ContentGuiFacadeInterface
    {
        $factory = new ContentGuiBusinessFactory();
        $config = new ContentGuiConfigTest();
        $factory->setConfig($config);

        $facade = new ContentGuiFacade();
        $facade->setFactory($factory);

        return $facade;
    }
}
