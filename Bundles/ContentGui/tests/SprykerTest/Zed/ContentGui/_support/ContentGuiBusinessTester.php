<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentGui;

use Codeception\Actor;
use Codeception\Scenario;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\LocalizedContentTransfer;
use Spryker\Zed\ContentGui\ContentGuiConfig;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiFacade getFacade()
 */
class ContentGuiBusinessTester extends Actor
{
    use _generated\ContentGuiBusinessTesterActions;

    protected const TEMPLATE_IDENTIFIER_DEFAULT = 'default';
    protected const TEMPLATE_IDENTIFIER_TOP_TITLE = 'top-title';
    protected const TEMPLATE_DISPLAY_NAME_DEFAULT = 'Default';
    protected const TEMPLATE_DISPLAY_NAME_TOP_TITLE = 'Top title';
    protected const TWIG_FUNCTION_TEMPLATE_BANNER = "{{ content_banner('%s', '%s') }}";
    protected const TWIG_FUNCTION_TEMPLATE_PRODUCT_ABSTRACT_LIST = "{{ content_product_abstract_list('%s', '%s') }}";
    protected const TYPE_ABSTRACT_PRODUCT_LIST = 'Abstract Product List';

    /**
     * @var \Generated\Shared\Transfer\ContentTransfer
     */
    protected $bannerContentTransfer;

    /**
     * @var \Generated\Shared\Transfer\ContentTransfer
     */
    protected $abstractProductListContentTransfer;

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->bannerContentTransfer = $this->createBannerContentItem();
        $this->abstractProductListContentTransfer = $this->createAbstractProductListContentItem();
    }

    /**
     * @param string|null $key
     *
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function createBannerContentItem(?string $key = null): ContentTransfer
    {
        $data = [
            ContentTransfer::CONTENT_TERM_KEY => 'Banner',
            ContentTransfer::CONTENT_TYPE_KEY => 'Banner',
            ContentTransfer::DESCRIPTION => 'Test Banner',
            ContentTransfer::NAME => 'Test Banner',
            ContentTransfer::KEY => $key ?: 'br-test',
            ContentTransfer::LOCALIZED_CONTENTS => [
                [
                    LocalizedContentTransfer::PARAMETERS => '{}',
                ],
            ],
        ];

        return $this->haveContent($data);
    }

    /**
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function createAbstractProductListContentItem(): ContentTransfer
    {
        $data = [
            ContentTransfer::CONTENT_TERM_KEY => 'Abstract Product List',
            ContentTransfer::CONTENT_TYPE_KEY => 'Abstract Product List',
            ContentTransfer::DESCRIPTION => 'Test Product List',
            ContentTransfer::NAME => 'Test Product List',
            ContentTransfer::KEY => 'apl-test',
            ContentTransfer::LOCALIZED_CONTENTS => [
                [
                    LocalizedContentTransfer::PARAMETERS => '{}',
                ],
            ],
        ];

        return $this->haveContent($data);
    }

    /**
     * @param bool $addLineBreak
     *
     * @return string
     */
    public function getOneTwigExpression(bool $addLineBreak = false): string
    {
        return $this->createTwigExpression($this->bannerContentTransfer, static::TEMPLATE_IDENTIFIER_DEFAULT)
            . ($addLineBreak ? "\n" : '');
    }

    /**
     * @param bool $addLineBreak
     *
     * @return string
     */
    public function getTwoSameTwigExpressions(bool $addLineBreak = false): string
    {
        return $this->createTwigExpression($this->bannerContentTransfer, static::TEMPLATE_IDENTIFIER_DEFAULT)
            . $this->createTwigExpression($this->bannerContentTransfer, static::TEMPLATE_IDENTIFIER_DEFAULT)
            . ($addLineBreak ? "\n" : '');
    }

    /**
     * @param bool $addLineBreak
     *
     * @return string
     */
    public function getTwoSameTwigExpressionsWithInvalidHtml(bool $addLineBreak = false): string
    {
        return $this->createTwigExpression($this->bannerContentTransfer, static::TEMPLATE_IDENTIFIER_DEFAULT)
            . '<p>' . $this->createTwigExpression($this->bannerContentTransfer, static::TEMPLATE_IDENTIFIER_DEFAULT)
            . ($addLineBreak ? "\n" : '');
    }

    /**
     * @param bool $addLineBreak
     *
     * @return string
     */
    public function getTwoDifferentTwigExpression(bool $addLineBreak = false): string
    {
        return $this->createTwigExpression($this->bannerContentTransfer, static::TEMPLATE_IDENTIFIER_DEFAULT)
            . $this->createTwigExpression($this->abstractProductListContentTransfer, static::TEMPLATE_IDENTIFIER_TOP_TITLE)
            . ($addLineBreak ? "\n" : '');
    }

    /**
     * @return string
     */
    public function getInvalidContentItemTwigExpression(): string
    {
        return "{{ content_banner('test', 'test') }}";
    }

    /**
     * @return string
     */
    public function getEmptyString(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getWrongHtml(): string
    {
        return '<p></p><div>';
    }

    /**
     * @return string
     */
    public function getStringWithPartOfTwigExpression(): string
    {
        return '{{ content_banner jjust text';
    }

    /**
     * @param bool $wrapper
     *
     * @return string
     */
    public function getOneHtmlWidget($wrapper = false): string
    {
        return $this->createWidget($this->bannerContentTransfer, $wrapper);
    }

    /**
     * @param bool $wrapper
     *
     * @return string
     */
    public function getTwoSameHtmlWidgets(bool $wrapper = false): string
    {
        return $this->createWidget($this->bannerContentTransfer, $wrapper)
            . $this->createWidget($this->bannerContentTransfer, $wrapper);
    }

    /**
     * @param bool $wrapper
     *
     * @return string
     */
    public function getTwoSameHtmlWidgetsWithInvalidHtml(bool $wrapper = false): string
    {
        return $this->createWidget($this->bannerContentTransfer, $wrapper)
            . '<p>' . $this->createWidget($this->bannerContentTransfer, $wrapper);
    }

    /**
     * @param bool $wrapper
     *
     * @return string
     */
    public function getTwoDifferentHtmlWidgets(bool $wrapper = false): string
    {
        return $this->createWidget($this->bannerContentTransfer, $wrapper)
            . $this->createWidget($this->abstractProductListContentTransfer, $wrapper);
    }

    /**
     * @return string
     */
    public function getInvalidHtmlWidget(): string
    {
        return '<span class="content-item-editor js-content-item-editor" '
                . 'contenteditable="false" '
                . 'data-type="' . $this->bannerContentTransfer->getContentTypeKey() . '" '
                . 'data-id="' . $this->bannerContentTransfer->getIdContent() . '" '
                . 'data-key="' . $this->bannerContentTransfer->getKey() . '" '
                . 'data-template="default" '
                . 'data-twig-expression="' . $this->createTwigExpression($this->bannerContentTransfer, 'default') . '">';
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     * @param bool $wrapper
     *
     * @return string
     */
    protected function createWidget(ContentTransfer $contentTransfer, bool $wrapper = false): string
    {
        $editorContentWidgetTemplate = $this->getConfig()->getEditorContentWidgetTemplate();

        $templateIdentifier = static::TEMPLATE_IDENTIFIER_DEFAULT;
        $templateDisplayName = static::TEMPLATE_DISPLAY_NAME_DEFAULT;

        if ($contentTransfer->getContentTypeKey() === static::TYPE_ABSTRACT_PRODUCT_LIST) {
            $templateIdentifier = static::TEMPLATE_IDENTIFIER_TOP_TITLE;
            $templateDisplayName = static::TEMPLATE_DISPLAY_NAME_TOP_TITLE;
        }

        $contentGuiConfig = $this->getConfig();
        $html = strtr($editorContentWidgetTemplate, [
            $contentGuiConfig->getParameterId() => $contentTransfer->getIdContent(),
            $contentGuiConfig->getParameterKey() => $contentTransfer->getKey(),
            $contentGuiConfig->getParameterType() => $contentTransfer->getContentTypeKey(),
            $contentGuiConfig->getParameterName() => $contentTransfer->getName(),
            $contentGuiConfig->getParameterTwigExpression() => $this->createTwigExpression($contentTransfer, $templateIdentifier),
            $contentGuiConfig->getParameterTemplate() => $templateIdentifier,
            $contentGuiConfig->getParameterTemplateDisplayName() => $templateDisplayName,
        ]);

        return $wrapper ? sprintf($contentGuiConfig->getEditorContentWidgetWrapper(), $html) : $html;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     * @param string $templateIdentifier
     *
     * @return string
     */
    protected function createTwigExpression(ContentTransfer $contentTransfer, string $templateIdentifier): string
    {
        $twigFunctionTemplate = static::TWIG_FUNCTION_TEMPLATE_BANNER;

        if ($contentTransfer->getContentTypeKey() === static::TYPE_ABSTRACT_PRODUCT_LIST) {
            $twigFunctionTemplate = static::TWIG_FUNCTION_TEMPLATE_PRODUCT_ABSTRACT_LIST;
        }

        return sprintf($twigFunctionTemplate, $contentTransfer->getKey(), $templateIdentifier);
    }

    /**
     * @return \Spryker\Zed\ContentGui\ContentGuiConfig
     */
    protected function getConfig(): ContentGuiConfig
    {
        return new ContentGuiConfig();
    }
}
