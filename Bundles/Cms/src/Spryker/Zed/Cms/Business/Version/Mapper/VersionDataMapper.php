<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Mapper;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsTemplateTransfer;
use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Generated\Shared\Transfer\CmsVersionTransfer;
use Orm\Zed\Cms\Persistence\Base\SpyCmsGlossaryKeyMapping;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes;
use Orm\Zed\Cms\Persistence\SpyCmsVersion;
use Spryker\Zed\Cms\Dependency\Service\CmsToUtilEncodingInterface;

class VersionDataMapper implements VersionDataMapperInterface
{

    /**
     * @var CmsToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @param CmsToUtilEncodingInterface $utilEncoding
     */
    public function __construct(CmsToUtilEncodingInterface $utilEncoding)
    {
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param CmsVersionDataTransfer $cmsVersionDataTransfer
     *
     * @return string
     */
    public function mapToJsonData(CmsVersionDataTransfer $cmsVersionDataTransfer)
    {
        return $this->utilEncoding->encodeJson($cmsVersionDataTransfer->toArray());
    }

    /**
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return CmsVersionDataTransfer
     */
    public function mapToCmsVersionDataTransfer(SpyCmsPage $cmsPageEntity)
    {
        $cmsVersionDataTransfer = new CmsVersionDataTransfer();

        $cmsTemplateTransfer = $this->mapToCmsTemplateData($cmsPageEntity);
        $cmsVersionDataTransfer->setCmsTemplate($cmsTemplateTransfer);

        $cmsPageTransfer =  $this->mapToCmsPageLocalizedAttributesData($cmsPageEntity);
        $cmsVersionDataTransfer->setCmsPage($cmsPageTransfer);

        $cmsGlossaryTransfer = $this->mapToCmsGlossaryKeyMappingsData($cmsPageEntity);
        $cmsVersionDataTransfer->setCmsGlossary($cmsGlossaryTransfer);

        return $cmsVersionDataTransfer;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsVersion $cmsVersionEntity
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function mapToCmsVersionTransfer(SpyCmsVersion $cmsVersionEntity)
    {
        $cmsVersionTransfer = new CmsVersionTransfer();
        $cmsVersionTransfer->fromArray($cmsVersionEntity->toArray(), true);

        return $cmsVersionTransfer;
    }

    /**
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return CmsTemplateTransfer
     */
    public function mapToCmsTemplateData(SpyCmsPage $cmsPageEntity)
    {
        $cmsTemplateTransfer = new CmsTemplateTransfer();
        $cmsTemplateTransfer->fromArray($cmsPageEntity->getCmsTemplate()->toArray(), true);

        return $cmsTemplateTransfer;
    }

    /**
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return CmsPageTransfer
     */
    public function mapToCmsPageLocalizedAttributesData(SpyCmsPage $cmsPageEntity)
    {
        $cmsPageTransfer = new CmsPageTransfer();
        $cmsPageTransfer->fromArray($cmsPageEntity->toArray(), true);
        $cmsPageTransfer->setFkPage($cmsPageEntity->getIdCmsPage());
        $cmsPageTransfer->setTemplateName($cmsPageEntity->getCmsTemplate()->getTemplateName());

        foreach ($cmsPageEntity->getSpyCmsPageLocalizedAttributessJoinLocale() as $spyCmsPageLocalizedAttributes) {
            $localeName = $spyCmsPageLocalizedAttributes->getLocale()->getLocaleName();
            $pageAttributeTransfer = $this->createCmsPageAttributesTransfer($spyCmsPageLocalizedAttributes, $localeName);
            $cmsPageTransfer->addPageAttribute($pageAttributeTransfer);
            $pageMetaAttributeTransfer = $this->createCmsPageMetaAttributesTransfer($spyCmsPageLocalizedAttributes, $localeName);
            $cmsPageTransfer->addMetaAttribute($pageMetaAttributeTransfer);
        }

        return $cmsPageTransfer;
    }

    /**
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return CmsGlossaryTransfer
     */
    public function mapToCmsGlossaryKeyMappingsData(SpyCmsPage $cmsPageEntity)
    {

        $cmsGlossaryTransfer = new CmsGlossaryTransfer();

        foreach ($cmsPageEntity->getSpyCmsGlossaryKeyMappingsJoinGlossaryKey() as $spyCmsGlossaryKeyMapping) {
            $cmsGlossaryAttributeTransfer = new CmsGlossaryAttributesTransfer();
            $cmsGlossaryAttributeTransfer->setPlaceholder($spyCmsGlossaryKeyMapping->getPlaceholder());
            $cmsGlossaryAttributeTransfer->setFkPage($cmsPageEntity->getIdCmsPage());
            $cmsGlossaryAttributeTransfer->setTranslationKey($spyCmsGlossaryKeyMapping->getGlossaryKey()->getKey());
            $cmsGlossaryAttributeTransfer->setTemplateName($cmsPageEntity->getCmsTemplate()->getTemplateName());
            $translations = $this->createCmsPlaceholderTranslationTransfers($spyCmsGlossaryKeyMapping);
            $cmsGlossaryAttributeTransfer->setTranslations($translations);
            $cmsGlossaryTransfer->addGlossaryAttribute($cmsGlossaryAttributeTransfer);
        }

        return $cmsGlossaryTransfer;
    }

    /**
     * @param SpyCmsGlossaryKeyMapping $spyCmsGlossaryKeyMapping
     *
     * @return array
     */
    protected function createCmsPlaceholderTranslationTransfers(SpyCmsGlossaryKeyMapping $spyCmsGlossaryKeyMapping)
    {
        $cmsGlossaryAttributeTransfers = new \ArrayObject();
        foreach ($spyCmsGlossaryKeyMapping->getGlossaryKey()->getSpyGlossaryTranslationsJoinLocale() as $glossaryTranslation) {
            $cmsPlaceholderTranslation = new CmsPlaceholderTranslationTransfer();
            $cmsPlaceholderTranslation->setTranslation($glossaryTranslation->getValue());
            $cmsPlaceholderTranslation->setLocaleName($glossaryTranslation->getLocale()->getLocaleName());
            $cmsGlossaryAttributeTransfers[] = $cmsPlaceholderTranslation;
        }

        return $cmsGlossaryAttributeTransfers;
    }

    /**
     * @param SpyCmsPageLocalizedAttributes $spyCmsPageLocalizedAttributes
     * @param string $localeName
     *
     * @return CmsPageAttributesTransfer
     */
    protected function createCmsPageAttributesTransfer(SpyCmsPageLocalizedAttributes $spyCmsPageLocalizedAttributes, $localeName)
    {
        $pageAttributeTransfer = new CmsPageAttributesTransfer();
        $pageAttributeTransfer->setName($spyCmsPageLocalizedAttributes->getName());
        $pageAttributeTransfer->setLocaleName($localeName);

        return $pageAttributeTransfer;
    }

    /**
     * @param SpyCmsPageLocalizedAttributes $spyCmsPageLocalizedAttributes
     * @param string $localeName
     *
     * @return CmsPageMetaAttributesTransfer
     */
    protected function createCmsPageMetaAttributesTransfer(SpyCmsPageLocalizedAttributes $spyCmsPageLocalizedAttributes, $localeName)
    {
        $pageMetaAttributeTransfer = new CmsPageMetaAttributesTransfer();
        $pageMetaAttributeTransfer->setLocaleName($localeName);
        $pageMetaAttributeTransfer->setMetaTitle($spyCmsPageLocalizedAttributes->getMetaTitle());
        $pageMetaAttributeTransfer->setMetaDescription($spyCmsPageLocalizedAttributes->getMetaDescription());
        $pageMetaAttributeTransfer->setMetaKeywords($spyCmsPageLocalizedAttributes->getMetaKeywords());

        return $pageMetaAttributeTransfer;
    }
}
