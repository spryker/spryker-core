<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter\CmsGui;

use ArrayObject;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\ContentGui\Business\Converter\AbstractCmsGlossaryConverter;

class CmsGuiGlossaryConverter extends AbstractCmsGlossaryConverter implements CmsGuiGlossaryConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertShortCodeToHtml(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        return $this->execute($cmsGlossaryTransfer, 'convertTranslationShortCodeToHtml');
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertHtmlToShortCode(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        return $this->execute($cmsGlossaryTransfer, 'convertTranslationHtmlToShortCode');
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     * @param string $methodName
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    protected function execute(CmsGlossaryTransfer $cmsGlossaryTransfer, string $methodName): CmsGlossaryTransfer
    {
        $cmsGlossaryAttributesTransfers = $cmsGlossaryTransfer->getGlossaryAttributes();

        foreach ($cmsGlossaryAttributesTransfers as $cmsGlossaryAttributesTransfer) {
            $cmsPlaceholderTranslationTransfers = $cmsGlossaryAttributesTransfer->getTranslations();
            $cmsPlaceholderTranslationTransfers = $this->convertTranslations($cmsPlaceholderTranslationTransfers, $methodName);
            $cmsGlossaryAttributesTransfer->setTranslations($cmsPlaceholderTranslationTransfers);
        }

        return $cmsGlossaryTransfer->setGlossaryAttributes($cmsGlossaryAttributesTransfers);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer[] $cmsPlaceholderTranslationTransfers
     * @param string $methodName
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer[]
     */
    protected function convertTranslations(ArrayObject $cmsPlaceholderTranslationTransfers, string $methodName): ArrayObject
    {
        foreach ($cmsPlaceholderTranslationTransfers as $cmsPlaceholderTranslationTransfer) {
            $cmsPlaceholderTranslation = $cmsPlaceholderTranslationTransfer->getTranslation();
            $cmsPlaceholderTranslation = $this->{$methodName}($cmsPlaceholderTranslation);
            $cmsPlaceholderTranslationTransfer->setTranslation($cmsPlaceholderTranslation);
        }

        return $cmsPlaceholderTranslationTransfers;
    }
}
