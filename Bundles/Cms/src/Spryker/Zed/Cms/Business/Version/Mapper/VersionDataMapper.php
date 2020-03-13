<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsTemplateTransfer;
use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Generated\Shared\Transfer\CmsVersionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Cms\Persistence\Base\SpyCmsGlossaryKeyMapping;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes;
use Orm\Zed\Cms\Persistence\SpyCmsVersion;
use Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationReaderInterface;
use Spryker\Zed\Cms\Dependency\Service\CmsToUtilEncodingInterface;

class VersionDataMapper implements VersionDataMapperInterface
{
    /**
     * @var \Spryker\Zed\Cms\Dependency\Service\CmsToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @var \Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationReaderInterface
     */
    protected $cmsPageStoreRelationReader;

    /**
     * @param \Spryker\Zed\Cms\Dependency\Service\CmsToUtilEncodingInterface $utilEncoding
     * @param \Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationReaderInterface $cmsPageStoreRelationReader
     */
    public function __construct(
        CmsToUtilEncodingInterface $utilEncoding,
        CmsPageStoreRelationReaderInterface $cmsPageStoreRelationReader
    ) {
        $this->utilEncoding = $utilEncoding;
        $this->cmsPageStoreRelationReader = $cmsPageStoreRelationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $cmsVersionDataTransfer
     *
     * @return string
     */
    public function mapToJsonData(CmsVersionDataTransfer $cmsVersionDataTransfer): string
    {
        return $this->utilEncoding->encodeJson($cmsVersionDataTransfer->toArray());
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function mapToCmsVersionDataTransfer(SpyCmsPage $cmsPageEntity): CmsVersionDataTransfer
    {
        $cmsVersionDataTransfer = new CmsVersionDataTransfer();

        $cmsTemplateTransfer = $this->mapToCmsTemplateData($cmsPageEntity);
        $cmsVersionDataTransfer->setCmsTemplate($cmsTemplateTransfer);

        $cmsPageTransfer = $this->mapToCmsPageLocalizedAttributesData($cmsPageEntity);
        $cmsPageTransfer = $this->mapToCmsPageStoreRelationData($cmsPageTransfer, $cmsPageEntity);
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
    public function mapToCmsVersionTransfer(SpyCmsVersion $cmsVersionEntity): CmsVersionTransfer
    {
        $cmsVersionTransfer = new CmsVersionTransfer();
        $cmsVersionTransfer->fromArray($cmsVersionEntity->toArray(), true);

        return $cmsVersionTransfer;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function mapToCmsTemplateData(SpyCmsPage $cmsPageEntity): CmsTemplateTransfer
    {
        $cmsTemplateTransfer = new CmsTemplateTransfer();
        $cmsTemplateTransfer->fromArray($cmsPageEntity->getCmsTemplate()->toArray(), true);

        return $cmsTemplateTransfer;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function mapToCmsPageLocalizedAttributesData(SpyCmsPage $cmsPageEntity): CmsPageTransfer
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
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function mapToCmsGlossaryKeyMappingsData(SpyCmsPage $cmsPageEntity): CmsGlossaryTransfer
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
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function mapToCmsPageStoreRelationData(CmsPageTransfer $cmsPageTransfer, SpyCmsPage $cmsPageEntity): CmsPageTransfer
    {
        $storeRelationTransfer = $this->cmsPageStoreRelationReader->getStoreRelation(
            (new StoreRelationTransfer())->setIdEntity($cmsPageEntity->getIdCmsPage())
        );

        $cmsPageTransfer->setStoreRelation($storeRelationTransfer);

        return $cmsPageTransfer;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\Base\SpyCmsGlossaryKeyMapping $spyCmsGlossaryKeyMapping
     *
     * @return \ArrayObject
     */
    protected function createCmsPlaceholderTranslationTransfers(SpyCmsGlossaryKeyMapping $spyCmsGlossaryKeyMapping): ArrayObject
    {
        $cmsGlossaryAttributeTransfers = new ArrayObject();
        foreach ($spyCmsGlossaryKeyMapping->getGlossaryKey()->getSpyGlossaryTranslationsJoinLocale() as $glossaryTranslation) {
            $cmsPlaceholderTranslation = new CmsPlaceholderTranslationTransfer();
            $cmsPlaceholderTranslation->setTranslation($glossaryTranslation->getValue());
            $cmsPlaceholderTranslation->setLocaleName($glossaryTranslation->getLocale()->getLocaleName());
            $cmsGlossaryAttributeTransfers[] = $cmsPlaceholderTranslation;
        }

        return $cmsGlossaryAttributeTransfers;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes $spyCmsPageLocalizedAttributes
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer
     */
    protected function createCmsPageAttributesTransfer(
        SpyCmsPageLocalizedAttributes $spyCmsPageLocalizedAttributes,
        string $localeName
    ): CmsPageAttributesTransfer {
        $pageAttributeTransfer = new CmsPageAttributesTransfer();
        $pageAttributeTransfer->setName($spyCmsPageLocalizedAttributes->getName());
        $pageAttributeTransfer->setLocaleName($localeName);

        return $pageAttributeTransfer;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes $spyCmsPageLocalizedAttributes
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CmsPageMetaAttributesTransfer
     */
    protected function createCmsPageMetaAttributesTransfer(
        SpyCmsPageLocalizedAttributes $spyCmsPageLocalizedAttributes,
        string $localeName
    ): CmsPageMetaAttributesTransfer {
        $pageMetaAttributeTransfer = new CmsPageMetaAttributesTransfer();
        $pageMetaAttributeTransfer->setLocaleName($localeName);
        $pageMetaAttributeTransfer->setMetaTitle($spyCmsPageLocalizedAttributes->getMetaTitle());
        $pageMetaAttributeTransfer->setMetaDescription($spyCmsPageLocalizedAttributes->getMetaDescription());
        $pageMetaAttributeTransfer->setMetaKeywords($spyCmsPageLocalizedAttributes->getMetaKeywords());

        return $pageMetaAttributeTransfer;
    }
}
