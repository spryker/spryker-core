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
    public function convertTwigExpressionToHtml(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        return $this->execute($cmsGlossaryTransfer, 'convertTranslationTwigExpressionToHtml');
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertHtmlToTwigExpression(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        return $this->execute($cmsGlossaryTransfer, 'convertTranslationHtmlToTwigExpression');
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
            if (!$cmsPlaceholderTranslation) {
                continue;
            }
            $cmsPlaceholderTranslation = $this->{$methodName}($cmsPlaceholderTranslation);
            $cmsPlaceholderTranslationTransfer->setTranslation($cmsPlaceholderTranslation);
        }

        return $cmsPlaceholderTranslationTransfers;
    }
}
