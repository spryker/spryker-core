<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Extractor;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Spryker\Zed\CmsCollector\Dependency\Service\CmsCollectorToUtilEncodingInterface;

class DataExtractor implements DataExtractorInterface
{

    /**
     * @var CmsCollectorToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @param CmsCollectorToUtilEncodingInterface $utilEncoding
     */
    public function __construct(CmsCollectorToUtilEncodingInterface $utilEncoding)
    {
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param string $data
     *
     * @return CmsVersionDataTransfer
     */
    public function extractCmsVersionDataTransfer($data)
    {
        $cmsDataArray = $this->utilEncoding->decodeJson($data, true);

        return (new CmsVersionDataTransfer())->fromArray($cmsDataArray);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     * @param $localeName
     *
     * @return array
     */
    public function extractPlaceholdersByLocale(CmsGlossaryTransfer $cmsGlossaryTransfer, $localeName)
    {
        $placeholders = [];
        foreach ($cmsGlossaryTransfer->getGlossaryAttributes() as $glossaryAttribute) {
            $placeholder = $glossaryAttribute->getPlaceholder();
            $translations = $glossaryAttribute->getTranslations();
            $placeholders[$placeholder] = $this->extractTranslationByLocales($translations, $localeName);
        }

        return $placeholders;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer|null
     */
    public function extractPageAttributeByLocale(CmsPageTransfer $cmsPageTransfer, $localeName)
    {
        foreach ($cmsPageTransfer->getPageAttributes() as $pageAttribute) {
            if ($pageAttribute->getLocaleName() === $localeName) {
                return $pageAttribute;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CmsPageMetaAttributesTransfer|null
     */
    public function extractMetaAttributeByLocales(CmsPageTransfer $cmsPageTransfer, $localeName)
    {
        foreach ($cmsPageTransfer->getMetaAttributes() as $metaAttribute) {
            if ($metaAttribute->getLocaleName() === $localeName) {
                return $metaAttribute;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer[]|\ArrayObject $translations
     * @param string $localeName
     *
     * @return string
     */
    protected function extractTranslationByLocales(\ArrayObject $translations, $localeName)
    {
        foreach ($translations as $translation) {
            if ($translation->getLocaleName() === $localeName) {
                return $translation->getTranslation();
            }
        }

        return '';
    }
}
