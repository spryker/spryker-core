<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter\CmsGui;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\ContentGui\Business\Converter\AbstractCmsGlossaryConverter;

class CmsGuiGlossaryConverter extends AbstractCmsGlossaryConverter implements CmsGuiGlossaryConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertTwigExpressionToHtml(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        $cmsGlossaryAttributesTransfers = $cmsGlossaryTransfer->getGlossaryAttributes();

        foreach ($cmsGlossaryAttributesTransfers as $cmsGlossaryAttributesTransfer) {
            $cmsPlaceholderTranslationTransfers = $cmsGlossaryAttributesTransfer->getTranslations();

            foreach ($cmsPlaceholderTranslationTransfers as $cmsPlaceholderTranslationTransfer) {
                $cmsPlaceholderTranslation = $cmsPlaceholderTranslationTransfer->getTranslation();

                if (!$cmsPlaceholderTranslation) {
                    continue;
                }

                $cmsPlaceholderTranslation = $this->convertTranslationTwigExpressionToHtml($cmsPlaceholderTranslation);
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
        $cmsGlossaryAttributesTransfers = $cmsGlossaryTransfer->getGlossaryAttributes();

        foreach ($cmsGlossaryAttributesTransfers as $cmsGlossaryAttributesTransfer) {
            $cmsPlaceholderTranslationTransfers = $cmsGlossaryAttributesTransfer->getTranslations();

            foreach ($cmsPlaceholderTranslationTransfers as $cmsPlaceholderTranslationTransfer) {
                $cmsPlaceholderTranslation = $cmsPlaceholderTranslationTransfer->getTranslation();

                if (!$cmsPlaceholderTranslation) {
                    continue;
                }

                $cmsPlaceholderTranslation = $this->convertTranslationHtmlToTwigExpression($cmsPlaceholderTranslation);
                $cmsPlaceholderTranslationTransfer->setTranslation($cmsPlaceholderTranslation);
            }

            $cmsGlossaryAttributesTransfer->setTranslations($cmsPlaceholderTranslationTransfers);
        }

        return $cmsGlossaryTransfer->setGlossaryAttributes($cmsGlossaryAttributesTransfers);
    }
}
