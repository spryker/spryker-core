<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter\CmsGui;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\ContentGui\Business\Converter\HtmlConverterInterface;
use Spryker\Zed\ContentGui\Business\Converter\TwigExpressionConverterInterface;

class CmsGuiGlossaryConverter implements CmsGuiGlossaryConverterInterface
{
    /**
     * @var \Spryker\Zed\ContentGui\Business\Converter\HtmlConverterInterface
     */
    protected $htmlToTwigExpressionConverter;

    /**
     * @var \Spryker\Zed\ContentGui\Business\Converter\TwigExpressionConverterInterface
     */
    protected $twigExpressionToHtmlConverter;

    /**
     * @param \Spryker\Zed\ContentGui\Business\Converter\HtmlConverterInterface $htmlToTwigExpressionConverter
     * @param \Spryker\Zed\ContentGui\Business\Converter\TwigExpressionConverterInterface $twigExpressionToHtmlConverter
     */
    public function __construct(
        HtmlConverterInterface $htmlToTwigExpressionConverter,
        TwigExpressionConverterInterface $twigExpressionToHtmlConverter
    ) {
        $this->htmlToTwigExpressionConverter = $htmlToTwigExpressionConverter;
        $this->twigExpressionToHtmlConverter = $twigExpressionToHtmlConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertTwigExpressionToHtml(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        $cmsGlossaryTransfer->requireGlossaryAttributes();
        $cmsGlossaryAttributesTransfers = $cmsGlossaryTransfer->getGlossaryAttributes();

        foreach ($cmsGlossaryAttributesTransfers as $cmsGlossaryAttributesTransfer) {
            $cmsGlossaryAttributesTransfer->requireTranslations();
            $cmsPlaceholderTranslationTransfers = $cmsGlossaryAttributesTransfer->getTranslations();

            foreach ($cmsPlaceholderTranslationTransfers as $cmsPlaceholderTranslationTransfer) {
                $cmsPlaceholderTranslation = $cmsPlaceholderTranslationTransfer->getTranslation();

                if (!$cmsPlaceholderTranslation) {
                    continue;
                }

                $cmsPlaceholderTranslation = $this->twigExpressionToHtmlConverter->convertTwigExpressionToHtml($cmsPlaceholderTranslation);
                $cmsPlaceholderTranslationTransfer->setTranslation($cmsPlaceholderTranslation);
            }

            $cmsGlossaryAttributesTransfer->setTranslations($cmsPlaceholderTranslationTransfers);
        }

        return $cmsGlossaryTransfer->setGlossaryAttributes($cmsGlossaryAttributesTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertHtmlToTwigExpression(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        $cmsGlossaryTransfer->requireGlossaryAttributes();
        $cmsGlossaryAttributesTransfers = $cmsGlossaryTransfer->getGlossaryAttributes();

        foreach ($cmsGlossaryAttributesTransfers as $cmsGlossaryAttributesTransfer) {
            $cmsGlossaryAttributesTransfer->requireTranslations();
            $cmsPlaceholderTranslationTransfers = $cmsGlossaryAttributesTransfer->getTranslations();

            foreach ($cmsPlaceholderTranslationTransfers as $cmsPlaceholderTranslationTransfer) {
                $cmsPlaceholderTranslation = $cmsPlaceholderTranslationTransfer->getTranslation();

                if (!$cmsPlaceholderTranslation) {
                    continue;
                }

                $cmsPlaceholderTranslation = $this->htmlToTwigExpressionConverter->convertHtmlToTwigExpression($cmsPlaceholderTranslation);
                $cmsPlaceholderTranslationTransfer->setTranslation($cmsPlaceholderTranslation);
            }

            $cmsGlossaryAttributesTransfer->setTranslations($cmsPlaceholderTranslationTransfers);
        }

        return $cmsGlossaryTransfer->setGlossaryAttributes($cmsGlossaryAttributesTransfers);
    }
}
