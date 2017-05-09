<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Extractor;

use ArrayObject;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Spryker\Zed\CmsCollector\Dependency\Service\CmsCollectorToUtilEncodingInterface;

class DataExtractor implements DataExtractorInterface
{

    /**
     * @var \Spryker\Zed\CmsCollector\Dependency\Service\CmsCollectorToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @param \Spryker\Zed\CmsCollector\Dependency\Service\CmsCollectorToUtilEncodingInterface $utilEncoding
     */
    public function __construct(CmsCollectorToUtilEncodingInterface $utilEncoding)
    {
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param string $data
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function extractCmsVersionDataTransfer($data)
    {
        $cmsDataArray = $this->utilEncoding->decodeJson($data, true);

        return (new CmsVersionDataTransfer())->fromArray($cmsDataArray);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     * @param string $localeName
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
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer
     */
    public function extractPageAttributeByLocale(CmsPageTransfer $cmsPageTransfer, $localeName)
    {
        foreach ($cmsPageTransfer->getPageAttributes() as $pageAttribute) {
            if ($pageAttribute->getLocaleName() === $localeName) {
                return $pageAttribute;
            }
        }

        return new CmsPageAttributesTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CmsPageMetaAttributesTransfer
     */
    public function extractMetaAttributeByLocales(CmsPageTransfer $cmsPageTransfer, $localeName)
    {
        foreach ($cmsPageTransfer->getMetaAttributes() as $metaAttribute) {
            if ($metaAttribute->getLocaleName() === $localeName) {
                return $metaAttribute;
            }
        }

        return new CmsPageMetaAttributesTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer[]|\ArrayObject $translations
     * @param string $localeName
     *
     * @return string
     */
    protected function extractTranslationByLocales(ArrayObject $translations, $localeName)
    {
        foreach ($translations as $translation) {
            if ($translation->getLocaleName() === $localeName) {
                return $translation->getTranslation();
            }
        }

        return '';
    }

}
