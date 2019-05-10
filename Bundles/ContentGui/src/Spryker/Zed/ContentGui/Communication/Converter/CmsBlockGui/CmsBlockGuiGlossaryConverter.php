<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Converter\CmsBlockGui;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Spryker\Zed\ContentGui\Communication\Converter\AbstractCmsGlossaryConverter;

class CmsBlockGuiGlossaryConverter extends AbstractCmsGlossaryConverter implements CmsBlockGuiGlossaryConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function convertTwigFunctionToHtml(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryPlaceholderTransfers = $cmsBlockGlossaryTransfer->getGlossaryPlaceholders();

        foreach ($cmsBlockGlossaryPlaceholderTransfers as $cmsBlockGlossaryPlaceholderTransferKey => $cmsBlockGlossaryPlaceholderTransfer) {
            $cmsBlockGlossaryPlaceholderTranslationTransfers = $cmsBlockGlossaryPlaceholderTransfer->getTranslations();

            foreach ($cmsBlockGlossaryPlaceholderTranslationTransfers as $cmsBlockGlossaryPlaceholderTranslationTransferKey => $cmsBlockGlossaryPlaceholderTranslationTransfer) {
                $cmsBlockGlossaryPlaceholderTranslation = $cmsBlockGlossaryPlaceholderTranslationTransfer->getTranslation();
                $cmsBlockGlossaryPlaceholderTranslation = $this->convertTwigFunctionToHtmlInTranslation($cmsBlockGlossaryPlaceholderTranslation);
                $cmsBlockGlossaryPlaceholderTranslationTransfers[$cmsBlockGlossaryPlaceholderTranslationTransferKey]->setTranslation($cmsBlockGlossaryPlaceholderTranslation);
            }

            $cmsBlockGlossaryPlaceholderTransfers[$cmsBlockGlossaryPlaceholderTransferKey]->setTranslations($cmsBlockGlossaryPlaceholderTranslationTransfers);
        }

        return $cmsBlockGlossaryTransfer->setGlossaryPlaceholders($cmsBlockGlossaryPlaceholderTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function convertHtmlToTwigFunction(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryPlaceholderTransfers = $cmsBlockGlossaryTransfer->getGlossaryPlaceholders();

        foreach ($cmsBlockGlossaryPlaceholderTransfers as $cmsBlockGlossaryPlaceholderTransferKey => $cmsBlockGlossaryPlaceholderTransfer) {
            $cmsBlockGlossaryPlaceholderTranslationTransfers = $cmsBlockGlossaryPlaceholderTransfer->getTranslations();

            foreach ($cmsBlockGlossaryPlaceholderTranslationTransfers as $cmsBlockGlossaryPlaceholderTranslationTransferKey => $cmsBlockGlossaryPlaceholderTranslationTransfer) {
                $cmsBlockGlossaryPlaceholderTranslation = $cmsBlockGlossaryPlaceholderTranslationTransfer->getTranslation();
                $cmsBlockGlossaryPlaceholderTranslation = $this->convertHtmlToTwigFunctionInTranslation($cmsBlockGlossaryPlaceholderTranslation);
                $cmsBlockGlossaryPlaceholderTranslationTransfers[$cmsBlockGlossaryPlaceholderTranslationTransferKey]->setTranslation($cmsBlockGlossaryPlaceholderTranslation);
            }

            $cmsBlockGlossaryPlaceholderTransfers[$cmsBlockGlossaryPlaceholderTransferKey]->setTranslations($cmsBlockGlossaryPlaceholderTranslationTransfers);
        }

        return $cmsBlockGlossaryTransfer->setGlossaryPlaceholders($cmsBlockGlossaryPlaceholderTransfers);
    }
}
