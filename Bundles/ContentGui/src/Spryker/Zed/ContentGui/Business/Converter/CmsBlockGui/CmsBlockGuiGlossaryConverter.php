<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter\CmsBlockGui;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Spryker\Zed\ContentGui\Business\Converter\HtmlToTwigExpressionsConverterInterface;
use Spryker\Zed\ContentGui\Business\Converter\TwigExpressionsToHtmlConverterInterface;

class CmsBlockGuiGlossaryConverter implements CmsBlockGuiGlossaryConverterInterface
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
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function convertTwigExpressionsToHtml(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryTransfer->requireGlossaryPlaceholders();
        $cmsBlockGlossaryPlaceholderTransfers = $cmsBlockGlossaryTransfer->getGlossaryPlaceholders();

        foreach ($cmsBlockGlossaryPlaceholderTransfers as $cmsBlockGlossaryPlaceholderTransfer) {
            $cmsBlockGlossaryPlaceholderTransfer->requireTranslations();
            $cmsBlockGlossaryPlaceholderTranslationTransfers = $cmsBlockGlossaryPlaceholderTransfer->getTranslations();

            foreach ($cmsBlockGlossaryPlaceholderTranslationTransfers as $cmsBlockGlossaryPlaceholderTranslationTransfer) {
                $cmsBlockGlossaryPlaceholderTranslation = $cmsBlockGlossaryPlaceholderTranslationTransfer->getTranslation();

                if (!$cmsBlockGlossaryPlaceholderTranslation) {
                    continue;
                }

                $cmsBlockGlossaryPlaceholderTranslation = $this->twigExpressionsToHtmlConverter->convert($cmsBlockGlossaryPlaceholderTranslation);
                $cmsBlockGlossaryPlaceholderTranslationTransfer->setTranslation($cmsBlockGlossaryPlaceholderTranslation);
            }

            $cmsBlockGlossaryPlaceholderTransfer->setTranslations($cmsBlockGlossaryPlaceholderTranslationTransfers);
        }

        return $cmsBlockGlossaryTransfer->setGlossaryPlaceholders($cmsBlockGlossaryPlaceholderTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function convertHtmlToTwigExpressions(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryTransfer->requireGlossaryPlaceholders();
        $cmsBlockGlossaryPlaceholderTransfers = $cmsBlockGlossaryTransfer->getGlossaryPlaceholders();

        foreach ($cmsBlockGlossaryPlaceholderTransfers as $cmsBlockGlossaryPlaceholderTransfer) {
            $cmsBlockGlossaryPlaceholderTransfer->requireTranslations();
            $cmsBlockGlossaryPlaceholderTranslationTransfers = $cmsBlockGlossaryPlaceholderTransfer->getTranslations();

            foreach ($cmsBlockGlossaryPlaceholderTranslationTransfers as $cmsBlockGlossaryPlaceholderTranslationTransfer) {
                $cmsBlockGlossaryPlaceholderTranslation = $cmsBlockGlossaryPlaceholderTranslationTransfer->getTranslation();

                if (!$cmsBlockGlossaryPlaceholderTranslation) {
                    continue;
                }

                $cmsBlockGlossaryPlaceholderTranslation = $this->htmlToTwigExpressionsConverter->convert($cmsBlockGlossaryPlaceholderTranslation);
                $cmsBlockGlossaryPlaceholderTranslationTransfer->setTranslation($cmsBlockGlossaryPlaceholderTranslation);
            }

            $cmsBlockGlossaryPlaceholderTransfer->setTranslations($cmsBlockGlossaryPlaceholderTranslationTransfers);
        }

        return $cmsBlockGlossaryTransfer->setGlossaryPlaceholders($cmsBlockGlossaryPlaceholderTransfers);
    }
}
