<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter\CmsBlockGui;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Spryker\Zed\ContentGui\Business\Converter\AbstractCmsGlossaryConverter;

class CmsBlockGuiGlossaryConverter extends AbstractCmsGlossaryConverter implements CmsBlockGuiGlossaryConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function convertTwigExpressionToHtml(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryPlaceholderTransfers = $cmsBlockGlossaryTransfer->getGlossaryPlaceholders();

        foreach ($cmsBlockGlossaryPlaceholderTransfers as $cmsBlockGlossaryPlaceholderTransfer) {
            $cmsBlockGlossaryPlaceholderTranslationTransfers = $cmsBlockGlossaryPlaceholderTransfer->getTranslations();

            foreach ($cmsBlockGlossaryPlaceholderTranslationTransfers as $cmsBlockGlossaryPlaceholderTranslationTransfer) {
                $cmsBlockGlossaryPlaceholderTranslation = $cmsBlockGlossaryPlaceholderTranslationTransfer->getTranslation();

                if (!$cmsBlockGlossaryPlaceholderTranslation) {
                    continue;
                }

                $cmsBlockGlossaryPlaceholderTranslation = $this->convertTranslationTwigExpressionToHtml($cmsBlockGlossaryPlaceholderTranslation);
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
    public function convertHtmlToTwigExpression(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryPlaceholderTransfers = $cmsBlockGlossaryTransfer->getGlossaryPlaceholders();

        foreach ($cmsBlockGlossaryPlaceholderTransfers as $cmsBlockGlossaryPlaceholderTransfer) {
            $cmsBlockGlossaryPlaceholderTranslationTransfers = $cmsBlockGlossaryPlaceholderTransfer->getTranslations();

            foreach ($cmsBlockGlossaryPlaceholderTranslationTransfers as $cmsBlockGlossaryPlaceholderTranslationTransfer) {
                $cmsBlockGlossaryPlaceholderTranslation = $cmsBlockGlossaryPlaceholderTranslationTransfer->getTranslation();

                if (!$cmsBlockGlossaryPlaceholderTranslation) {
                    continue;
                }

                $cmsBlockGlossaryPlaceholderTranslation = $this->convertTranslationHtmlToTwigExpression($cmsBlockGlossaryPlaceholderTranslation);
                $cmsBlockGlossaryPlaceholderTranslationTransfer->setTranslation($cmsBlockGlossaryPlaceholderTranslation);
            }

            $cmsBlockGlossaryPlaceholderTransfer->setTranslations($cmsBlockGlossaryPlaceholderTranslationTransfers);
        }

        return $cmsBlockGlossaryTransfer->setGlossaryPlaceholders($cmsBlockGlossaryPlaceholderTransfers);
    }
}
