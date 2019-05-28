<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentGui;

use Codeception\Actor;
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
    protected const TWIG_FUNCTION_TEMPLATE_BANNER = '{{ content_banner(%d, \'%s\') }}';
    protected const TWIG_FUNCTION_TEMPLATE_PRODUCT_ABSTRACT_LIST = '{{ content_product_abstract_list(%d, \'%s\') }}';
    protected const TYPE_ABSTRACT_PRODUCT_LIST = 'Abstract Product List';

    /**
     * @return \Generated\Shared\Transfer\ContentTransfer
     */
    public function createBannerContentItem(): ContentTransfer
    {
        $data = [
            ContentTransfer::CONTENT_TERM_KEY => 'Banner',
            ContentTransfer::CONTENT_TYPE_KEY => 'Banner',
            ContentTransfer::DESCRIPTION => 'Test Banner',
            ContentTransfer::NAME => 'Test Banner',
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
            ContentTransfer::LOCALIZED_CONTENTS => [
                [
                    LocalizedContentTransfer::PARAMETERS => '{}',
                ],
            ],
        ];

        return $this->haveContent($data);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $bannerContentTransfer
     * @param bool $addLineBreak
     *
     * @return string
     */
    public function getOneTwigExpressionInString(ContentTransfer $bannerContentTransfer, bool $addLineBreak = false): string
    {
        return $this->createTwigExpression($bannerContentTransfer, static::TEMPLATE_IDENTIFIER_DEFAULT)
            . ($addLineBreak ? "\n" : '');
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $bannerContentTransfer
     * @param bool $addLineBreak
     *
     * @return string
     */
    public function getTwoSameTwigExpressionsInString(ContentTransfer $bannerContentTransfer, bool $addLineBreak = false): string
    {
        return $this->createTwigExpression($bannerContentTransfer, static::TEMPLATE_IDENTIFIER_DEFAULT)
            . $this->createTwigExpression($bannerContentTransfer, static::TEMPLATE_IDENTIFIER_DEFAULT)
            . ($addLineBreak ? "\n" : '');
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $bannerContentTransfer
     * @param \Generated\Shared\Transfer\ContentTransfer $abstractProductListContentTransfer
     * @param bool $addLineBreak
     *
     * @return string
     */
    public function getTwoDifferentTwigExpressionInString(
        ContentTransfer $bannerContentTransfer,
        ContentTransfer $abstractProductListContentTransfer,
        bool $addLineBreak = false
    ): string {
        return $this->createTwigExpression($bannerContentTransfer, static::TEMPLATE_IDENTIFIER_DEFAULT)
            . $this->createTwigExpression($abstractProductListContentTransfer, static::TEMPLATE_IDENTIFIER_TOP_TITLE)
            . ($addLineBreak ? "\n" : '');
    }

    /**
     * @return string
     */
    public function getStringWithoutTwigExpressionsAndWidgets(): string
    {
        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $bannerContentTransfer
     *
     * @return string
     */
    public function getOneHtmlWidgetInString(ContentTransfer $bannerContentTransfer): string
    {
        return $this->createWidget($bannerContentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $bannerContentTransfer
     *
     * @return string
     */
    public function getTwoSameHtmlWidgetsInString(ContentTransfer $bannerContentTransfer): string
    {
        return $this->createWidget($bannerContentTransfer)
            . $this->createWidget($bannerContentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $bannerContentTransfer
     * @param \Generated\Shared\Transfer\ContentTransfer $abstractProductListContentTransfer
     *
     * @return string
     */
    public function getTwoDifferentHtmlWidgetsInString(ContentTransfer $bannerContentTransfer, ContentTransfer $abstractProductListContentTransfer): string
    {
        return $this->createWidget($bannerContentTransfer)
            . $this->createWidget($abstractProductListContentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return string
     */
    protected function createWidget(ContentTransfer $contentTransfer): string
    {
        $editorContentWidgetTemplate = $this->getConfig()->getEditorContentWidgetTemplate();

        $templateIdentifier = static::TEMPLATE_IDENTIFIER_DEFAULT;
        $templateDisplayName = static::TEMPLATE_DISPLAY_NAME_DEFAULT;

        if ($contentTransfer->getContentTypeKey() === static::TYPE_ABSTRACT_PRODUCT_LIST) {
            $templateIdentifier = static::TEMPLATE_IDENTIFIER_TOP_TITLE;
            $templateDisplayName = static::TEMPLATE_DISPLAY_NAME_TOP_TITLE;
        }

        $html = strtr($editorContentWidgetTemplate, [
            $this->getConfig()->getParameterId() => $contentTransfer->getIdContent(),
            $this->getConfig()->getParameterType() => $contentTransfer->getContentTypeKey(),
            $this->getConfig()->getParameterName() => $contentTransfer->getName(),
            $this->getConfig()->getParameterTwigExpression() => $this->createTwigExpression($contentTransfer, $templateIdentifier),
            $this->getConfig()->getParameterTemplate() => $templateIdentifier,
            $this->getConfig()->getParameterTemplateDisplayName() => $templateDisplayName,
        ]);

        return sprintf($this->getConfig()->getEditorContentWidgetWrapper(), $html);
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

        return sprintf($twigFunctionTemplate, $contentTransfer->getIdContent(), $templateIdentifier);
    }

    /**
     * @return \Spryker\Zed\ContentGui\ContentGuiConfig
     */
    protected function getConfig(): ContentGuiConfig
    {
        return new ContentGuiConfig();
    }
}
