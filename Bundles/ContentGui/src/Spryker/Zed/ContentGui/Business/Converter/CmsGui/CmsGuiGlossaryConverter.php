<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter\CmsGui;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\ContentGui\Business\Converter\HtmlToTwigExpressionsConverterInterface;
use Spryker\Zed\ContentGui\Business\Converter\TwigExpressionsToHtmlConverterInterface;

class CmsGuiGlossaryConverter implements CmsGuiGlossaryConverterInterface
{
    /**
     * @var \Spryker\Zed\ContentGui\Business\Converter\HtmlToTwigExpressionsConverterInterface
     */
    protected $htmlToTwigExpressionsConverter;

    /**
     * @var \Spryker\Zed\ContentGui\Business\Converter\TwigExpressionsToHtmlConverterInterface
     */
    protected $twigExpressionsToHtmlConverter;

    /**
     * @param \Spryker\Zed\ContentGui\Business\Converter\HtmlToTwigExpressionsConverterInterface $htmlToTwigExpressionConverter
     * @param \Spryker\Zed\ContentGui\Business\Converter\TwigExpressionsToHtmlConverterInterface $twigExpressionToHtmlConverter
     */
    public function __construct(
        HtmlToTwigExpressionsConverterInterface $htmlToTwigExpressionConverter,
        TwigExpressionsToHtmlConverterInterface $twigExpressionToHtmlConverter
    ) {
        $this->htmlToTwigExpressionsConverter = $htmlToTwigExpressionConverter;
        $this->twigExpressionsToHtmlConverter = $twigExpressionToHtmlConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertTwigExpressionsToHtml(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
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

                $cmsPlaceholderTranslation = $this->twigExpressionsToHtmlConverter->convert($cmsPlaceholderTranslation);
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
    public function convertHtmlToTwigExpressions(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
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

                $cmsPlaceholderTranslation = $this->htmlToTwigExpressionsConverter->convert($cmsPlaceholderTranslation);
                $cmsPlaceholderTranslationTransfer->setTranslation($cmsPlaceholderTranslation);
            }

            $cmsGlossaryAttributesTransfer->setTranslations($cmsPlaceholderTranslationTransfers);
        }

        return $cmsGlossaryTransfer->setGlossaryAttributes($cmsGlossaryAttributesTransfers);
    }
}
