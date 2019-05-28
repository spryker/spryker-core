<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business\Converter\CmsBlockGui;

use ArrayObject;
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
        return $this->execute($cmsBlockGlossaryTransfer, 'convertTranslationTwigExpressionToHtml');
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function convertHtmlToTwigExpression(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        return $this->execute($cmsBlockGlossaryTransfer, 'convertTranslationHtmlToTwigExpression');
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     * @param string $methodName
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    protected function execute(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer, string $methodName): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossaryPlaceholderTransfers = $cmsBlockGlossaryTransfer->getGlossaryPlaceholders();

        foreach ($cmsBlockGlossaryPlaceholderTransfers as $cmsBlockGlossaryPlaceholderTransfer) {
            $cmsBlockGlossaryPlaceholderTranslationTransfers = $cmsBlockGlossaryPlaceholderTransfer->getTranslations();
            $cmsBlockGlossaryPlaceholderTranslationTransfers = $this->convertTranslations($cmsBlockGlossaryPlaceholderTranslationTransfers, $methodName);
            $cmsBlockGlossaryPlaceholderTransfer->setTranslations($cmsBlockGlossaryPlaceholderTranslationTransfers);
        }

        return $cmsBlockGlossaryTransfer->setGlossaryPlaceholders($cmsBlockGlossaryPlaceholderTransfers);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer[] $cmsBlockGlossaryPlaceholderTranslationTransfers
     * @param string $methodName
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer[]
     */
    protected function convertTranslations(ArrayObject $cmsBlockGlossaryPlaceholderTranslationTransfers, string $methodName): ArrayObject
    {
        foreach ($cmsBlockGlossaryPlaceholderTranslationTransfers as $cmsBlockGlossaryPlaceholderTranslationTransfer) {
            $cmsBlockGlossaryPlaceholderTranslation = $cmsBlockGlossaryPlaceholderTranslationTransfer->getTranslation();
            if (!$cmsBlockGlossaryPlaceholderTranslation) {
                continue;
            }
            $cmsBlockGlossaryPlaceholderTranslation = $this->{$methodName}($cmsBlockGlossaryPlaceholderTranslation);
            $cmsBlockGlossaryPlaceholderTranslationTransfer->setTranslation($cmsBlockGlossaryPlaceholderTranslation);
        }

        return $cmsBlockGlossaryPlaceholderTranslationTransfers;
    }
}
