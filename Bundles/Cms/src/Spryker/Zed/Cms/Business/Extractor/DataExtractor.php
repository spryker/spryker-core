<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Extractor;

use ArrayObject;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Generated\Shared\Transfer\LocaleCmsPageDataTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Cms\Dependency\Service\CmsToUtilEncodingInterface;

class DataExtractor implements DataExtractorInterface
{
    /**
     * @var \Spryker\Zed\Cms\Dependency\Service\CmsToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @param \Spryker\Zed\Cms\Dependency\Service\CmsToUtilEncodingInterface $utilEncoding
     */
    public function __construct(CmsToUtilEncodingInterface $utilEncoding)
    {
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param string $data
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function extractCmsVersionDataTransfer(string $data): CmsVersionDataTransfer
    {
        $cmsDataArray = $this->utilEncoding->decodeJson($data, true);

        return (new CmsVersionDataTransfer())->fromArray($cmsDataArray);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $cmsVersionDataTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocaleCmsPageDataTransfer
     */
    public function extractLocaleCmsPageDataTransfer(CmsVersionDataTransfer $cmsVersionDataTransfer, LocaleTransfer $localeTransfer): LocaleCmsPageDataTransfer
    {
        $cmsMetaAttributeTransfer = $this->extractMetaAttributeByLocales($cmsVersionDataTransfer->getCmsPage(), $localeTransfer->getLocaleName());
        $cmsPageAttributeTransfer = $this->extractPageAttributeByLocale($cmsVersionDataTransfer->getCmsPage(), $localeTransfer->getLocaleName());

        $localeCmsPageDataTransfer = (new LocaleCmsPageDataTransfer())
            ->setIdCmsPage($cmsVersionDataTransfer->getCmsPage()->getFkPage())
            ->setMetaDescription($cmsMetaAttributeTransfer->getMetaDescription())
            ->setMetaKeywords($cmsMetaAttributeTransfer->getMetaKeywords())
            ->setMetaTitle($cmsMetaAttributeTransfer->getMetaTitle())
            ->setPlaceholders($this->extractPlaceholdersByLocale($cmsVersionDataTransfer->getCmsGlossary(), $localeTransfer->getLocaleName()))
            ->setName($cmsPageAttributeTransfer->getName())
            ->setTemplatePath($cmsVersionDataTransfer->getCmsTemplate()->getTemplatePath());

        return $localeCmsPageDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     * @param string|null $localeName
     *
     * @return array
     */
    protected function extractPlaceholdersByLocale(CmsGlossaryTransfer $cmsGlossaryTransfer, ?string $localeName): array
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
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer
     */
    protected function extractPageAttributeByLocale(CmsPageTransfer $cmsPageTransfer, ?string $localeName): CmsPageAttributesTransfer
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
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\CmsPageMetaAttributesTransfer
     */
    protected function extractMetaAttributeByLocales(CmsPageTransfer $cmsPageTransfer, ?string $localeName): CmsPageMetaAttributesTransfer
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
     * @return string|null
     */
    protected function extractTranslationByLocales(ArrayObject $translations, string $localeName): ?string
    {
        foreach ($translations as $translation) {
            if ($translation->getLocaleName() === $localeName) {
                return $translation->getTranslation();
            }
        }

        return '';
    }
}
