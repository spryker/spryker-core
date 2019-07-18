<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsPageDataImport\Business\CmsPage;

use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Cms\Business\Mapping\GlossaryKeyMappingManager;
use Spryker\Zed\CmsPageDataImport\Business\DataSet\CmsPageDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\LocalizedAttributesExtractorStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Glossary\Dependency\GlossaryEvents;
use Spryker\Zed\Url\Dependency\UrlEvents;

class CmsPageWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    public const BULK_SIZE = 20;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $templateEntity = SpyCmsTemplateQuery::create()
            ->findOneByTemplateName($dataSet[CmsPageDataSet::KEY_TEMPLATE_NAME]);

        $cmsPageEntity = SpyCmsPageQuery::create()
            ->filterByPageKey($dataSet[CmsPageDataSet::KEY_PAGE_KEY])
            ->findOneOrCreate();

        $cmsPageEntity
            ->setFkTemplate($templateEntity->getIdCmsTemplate())
            ->setIsActive($dataSet[CmsPageDataSet::KEY_IS_ACTIVE])
            ->setIsSearchable($dataSet[CmsPageDataSet::KEY_IS_SEARCHABLE]);

        if ($cmsPageEntity->isNew() || $cmsPageEntity->isModified()) {
            $cmsPageEntity->save();
        }

        foreach ($dataSet[LocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES] as $idLocale => $attributes) {
            $this->addCmsPageLocalizedAttributes($cmsPageEntity, $idLocale, $attributes);

            $this->addCmsPageUrl($cmsPageEntity, $idLocale, $attributes);
        }

        foreach ($dataSet[CmsPageDataSet::KEY_PLACEHOLDER] as $idLocale => $placeholder) {
            foreach ($placeholder as $key => $value) {
                $this->addCmsPageGlossary($key, $cmsPageEntity, $idLocale, $value);
            }
        }
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     * @param int $idLocale
     * @param array $attributes
     *
     * @return void
     */
    protected function addCmsPageLocalizedAttributes(SpyCmsPage $cmsPageEntity, int $idLocale, array $attributes): void
    {
        $localizedAttributesEntity = SpyCmsPageLocalizedAttributesQuery::create()
            ->filterByFkCmsPage($cmsPageEntity->getIdCmsPage())
            ->filterByFkLocale($idLocale)
            ->findOneOrCreate();

        $localizedAttributesEntity->fromArray($attributes);

        if ($localizedAttributesEntity->isNew() || $localizedAttributesEntity->isModified()) {
            $localizedAttributesEntity->save();
        }
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     * @param int $idLocale
     * @param array $attributes
     *
     * @return void
     */
    protected function addCmsPageUrl(SpyCmsPage $cmsPageEntity, int $idLocale, array $attributes): void
    {
        $urlEntity = SpyUrlQuery::create()
            ->filterByFkResourcePage($cmsPageEntity->getIdCmsPage())
            ->filterByFkLocale($idLocale)
            ->findOneOrCreate();

        $urlEntity
            ->setUrl($attributes[CmsPageDataSet::KEY_URL]);

        if ($urlEntity->isNew() || $urlEntity->isModified()) {
            $urlEntity->save();
        }

        $this->addPublishEvents(UrlEvents::URL_PUBLISH, $urlEntity->getIdUrl());
    }

    /**
     * @param string $key
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     * @param int $idLocale
     * @param string $value
     *
     * @return void
     */
    protected function addCmsPageGlossary(string $key, SpyCmsPage $cmsPageEntity, int $idLocale, string $value): void
    {
        $uniquePlaceholder = $key . '-' . $cmsPageEntity->getIdCmsPage();

        $keyName = GlossaryKeyMappingManager::GENERATED_GLOSSARY_KEY_PREFIX . '.';
        $keyName .= str_replace([' ', '.'], '-', $cmsPageEntity->getCmsTemplate()->getTemplateName()) . '.';
        $keyName .= str_replace([' ', '.'], '-', $uniquePlaceholder);
        $keyName .= 0;

        $glossaryKeyEntity = SpyGlossaryKeyQuery::create()
            ->filterByKey($keyName)
            ->findOneOrCreate();

        $glossaryKeyEntity->save();

        $glossaryTranslationEntity = SpyGlossaryTranslationQuery::create()
            ->filterByFkGlossaryKey($glossaryKeyEntity->getIdGlossaryKey())
            ->filterByFkLocale($idLocale)
            ->findOneOrCreate();

        $glossaryTranslationEntity
            ->setValue($value);

        if ($glossaryTranslationEntity->isNew() || $glossaryTranslationEntity->isModified()) {
            $glossaryTranslationEntity->save();
        }

        $pageKeyMappingEntity = SpyCmsGlossaryKeyMappingQuery::create()
            ->filterByFkGlossaryKey($glossaryKeyEntity->getIdGlossaryKey())
            ->filterByFkPage($cmsPageEntity->getIdCmsPage())
            ->findOneOrCreate();

        $pageKeyMappingEntity
            ->setPlaceholder($key);

        if ($pageKeyMappingEntity->isNew() || $pageKeyMappingEntity->isModified()) {
            $pageKeyMappingEntity->save();
        }

        $this->addPublishEvents(GlossaryEvents::GLOSSARY_KEY_PUBLISH, $glossaryTranslationEntity->getFkGlossaryKey());
    }
}
