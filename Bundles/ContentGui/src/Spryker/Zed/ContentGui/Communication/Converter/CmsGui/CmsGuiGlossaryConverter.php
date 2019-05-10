<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Converter\CmsGui;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\ContentGui\Communication\Converter\AbstractCmsGlossaryConverter;

class CmsGuiGlossaryConverter extends AbstractCmsGlossaryConverter implements CmsGuiGlossaryConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertTwigFunctionToHtml(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        $cmsGlossaryAttributesTransfers = $cmsGlossaryTransfer->getGlossaryAttributes();

        foreach ($cmsGlossaryAttributesTransfers as $cmsGlossaryAttributesTransferKey => $cmsGlossaryAttributesTransfer) {
            $cmsPlaceholderTranslationTransfers = $cmsGlossaryAttributesTransfer->getTranslations();

            foreach ($cmsPlaceholderTranslationTransfers as $cmsPlaceholderTranslationTransferKey => $cmsPlaceholderTranslationTransfer) {
                $cmsPlaceholderTranslation = $cmsPlaceholderTranslationTransfer->getTranslation();
                $cmsPlaceholderTranslation = $this->convertTwigFunctionToHtmlInTranslation($cmsPlaceholderTranslation);
                $cmsPlaceholderTranslationTransfers[$cmsPlaceholderTranslationTransferKey]->setTranslation($cmsPlaceholderTranslation);
            }

            $cmsGlossaryAttributesTransfers[$cmsGlossaryAttributesTransferKey]->setTranslations($cmsPlaceholderTranslationTransfers);
        }

        return $cmsGlossaryTransfer->setGlossaryAttributes($cmsGlossaryAttributesTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertHtmlToTwigFunction(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        $cmsGlossaryAttributesTransfers = $cmsGlossaryTransfer->getGlossaryAttributes();

        foreach ($cmsGlossaryAttributesTransfers as $cmsGlossaryAttributesTransferKey => $cmsGlossaryAttributesTransfer) {
            $cmsPlaceholderTranslationTransfers = $cmsGlossaryAttributesTransfer->getTranslations();

            foreach ($cmsPlaceholderTranslationTransfers as $cmsPlaceholderTranslationTransferKey => $cmsPlaceholderTranslationTransfer) {
                $cmsPlaceholderTranslation = $cmsPlaceholderTranslationTransfer->getTranslation();
                $cmsPlaceholderTranslation = $this->convertHtmlToTwigFunctionInTranslation($cmsPlaceholderTranslation);
                $cmsPlaceholderTranslationTransfers[$cmsPlaceholderTranslationTransferKey]->setTranslation($cmsPlaceholderTranslation);
            }

            $cmsGlossaryAttributesTransfers[$cmsGlossaryAttributesTransferKey]->setTranslations($cmsPlaceholderTranslationTransfers);
        }

        return $cmsGlossaryTransfer->setGlossaryAttributes($cmsGlossaryAttributesTransfers);
    }
}
