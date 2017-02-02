<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Mapping;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping;
use Spryker\Zed\Cms\Business\Exception\MappingAmbiguousException;
use Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException;
use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsGlossarySaver implements CmsGlossarySaverInterface
{

    const GENERATED_GLOSSARY_KEY_PREFIX = 'generated.cms';

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface $glossaryFacade
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsToGlossaryInterface $glossaryFacade
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function saveCmsGlossary(CmsGlossaryTransfer $cmsGlossaryTransfer)
    {
        $this->cmsQueryContainer->getConnection()->beginTransaction();

        foreach ($cmsGlossaryTransfer->getGlossaryAttributes() as $glossaryAttributesTransfer) {

            $translationKey = $this->resolveTranslationKey($glossaryAttributesTransfer);
            $glossaryAttributesTransfer->setTranslationKey($translationKey);

            $this->translatePlaceholder($glossaryAttributesTransfer, $translationKey);

            $idCmsGlossaryMapping = $this->saveCmsGlossaryKeyMapping($glossaryAttributesTransfer);
            $glossaryAttributesTransfer->setFkCmsGlossaryMapping($idCmsGlossaryMapping);

        }

        $this->cmsQueryContainer->getConnection()->commit();

        return $cmsGlossaryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $glossaryAttributesTransfer
     *
     * @return int
     */
    public function saveCmsGlossaryKeyMapping(CmsGlossaryAttributesTransfer $glossaryAttributesTransfer)
    {
        if ($glossaryAttributesTransfer->getFkCmsGlossaryMapping() === null) {
            return $this->createPageKeyMapping($glossaryAttributesTransfer);
        } else {
            return $this->updatePageKeyMapping($glossaryAttributesTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $cmsGlossaryAttributesTransfer
     *
     * @return int
     */
    protected function createPageKeyMapping(CmsGlossaryAttributesTransfer $cmsGlossaryAttributesTransfer)
    {
        $this->checkPagePlaceholderNotAmbiguous(
            $cmsGlossaryAttributesTransfer->getFkPage(),
            $cmsGlossaryAttributesTransfer->getPlaceholder()
        );

        $cmsGlossaryKeyMappingEntity = new SpyCmsGlossaryKeyMapping();
        $cmsGlossaryKeyMappingEntity->fromArray($cmsGlossaryAttributesTransfer->toArray());

        $cmsGlossaryKeyMappingEntity->save();

        return $cmsGlossaryKeyMappingEntity->getPrimaryKey();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $cmsGlossaryAttributesTransfer
     *
     * @return int
     */
    protected function updatePageKeyMapping(CmsGlossaryAttributesTransfer $cmsGlossaryAttributesTransfer)
    {
        $glossaryKeyMappingEntity = $this->getGlossaryKeyMappingById($cmsGlossaryAttributesTransfer->getFkCmsGlossaryMapping());
        $glossaryKeyMappingEntity->fromArray($cmsGlossaryAttributesTransfer->toArray());

        if (!$glossaryKeyMappingEntity->isModified()) {
            return $glossaryKeyMappingEntity->getPrimaryKey();
        }

        $isPlaceholderModified = $glossaryKeyMappingEntity->isColumnModified(SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER);
        $isPageIdModified = $glossaryKeyMappingEntity->isColumnModified(SpyCmsGlossaryKeyMappingTableMap::COL_FK_PAGE);

        if ($isPlaceholderModified || $isPageIdModified) {
            $this->checkPagePlaceholderNotAmbiguous(
                $cmsGlossaryAttributesTransfer->getFkPage(),
                $cmsGlossaryAttributesTransfer->getPlaceholder()
            );
        }

        $glossaryKeyMappingEntity->save();

        return $glossaryKeyMappingEntity->getPrimaryKey();
    }

    /**
     * @param int $idMapping
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping
     */
    protected function getGlossaryKeyMappingById($idMapping)
    {
        $mappingEntity = $this->cmsQueryContainer->queryGlossaryKeyMappingById($idMapping)
            ->findOne();

        if (!$mappingEntity) {
            throw new MissingGlossaryKeyMappingException(sprintf('Tried to retrieve a missing glossary key mapping with id %s', $idMapping));
        }

        return $mappingEntity;
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MappingAmbiguousException
     *
     * @return void
     */
    protected function checkPagePlaceholderNotAmbiguous($idPage, $placeholder)
    {
        if ($this->hasPagePlaceholderMapping($idPage, $placeholder)) {
            throw new MappingAmbiguousException(sprintf('Tried to create an ambiguous mapping for placeholder %s on page %s', $placeholder, $idPage));
        }
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return bool
     */
    public function hasPagePlaceholderMapping($idPage, $placeholder)
    {
        $mappingCount = $this->cmsQueryContainer
            ->queryGlossaryKeyMapping($idPage, $placeholder)
            ->count();

        return $mappingCount > 0;
    }

    /**
     * @param string $templateName
     * @param string $placeholder
     * @param bool $autoIncrement
     *
     * @return string
     */
    protected function generateGlossaryKeyName($templateName, $placeholder, $autoIncrement = true)
    {
        $keyName = self::GENERATED_GLOSSARY_KEY_PREFIX . '.';
        $keyName .= str_replace([' ', '.'], '-', $templateName) . '.';
        $keyName .= str_replace([' ', '.'], '-', $placeholder);

        $index = 0;

        $candidate = $keyName . $index;

        while ($this->glossaryFacade->hasKey($candidate) && $autoIncrement === true) {
            $candidate = $keyName . ++$index;
        }

        return $candidate;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $glossaryAttributesTransfer
     *
     * @return string
     */
    protected function resolveTranslationKey(CmsGlossaryAttributesTransfer $glossaryAttributesTransfer)
    {
        $translationKey = $glossaryAttributesTransfer->getTranslationKey();
        if (!$glossaryAttributesTransfer->getTranslationKey()) {
            $translationKey = $this->generateGlossaryKeyName(
                $glossaryAttributesTransfer->getTemplateName(),
                $glossaryAttributesTransfer->getPlaceholder()
            );
        }
        return $translationKey;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $glossaryAttributesTransfer
     * @param string $translationKey
     *
     * @return void
     */
    protected function translatePlaceholder(CmsGlossaryAttributesTransfer $glossaryAttributesTransfer, $translationKey)
    {
        foreach ($glossaryAttributesTransfer->getTranslations() as $glossaryTranslationTransfer) {
            $keyTranslationTransfer = $this->createTranslationTransfer($translationKey, $glossaryTranslationTransfer);
            $this->glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);
        }

        $glossaryKey = $this->cmsQueryContainer
            ->queryKey($translationKey)
            ->findOne();

        $glossaryAttributesTransfer->setFkGlossaryKey($glossaryKey->getIdGlossaryKey());
    }

    /**
     * @param string $translationKey
     * @param \Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer $glossaryTranslationTransfer
     *
     * @return \Generated\Shared\Transfer\KeyTranslationTransfer
     */
    protected function createTranslationTransfer($translationKey, CmsPlaceholderTranslationTransfer $glossaryTranslationTransfer)
    {
        $keyTranslationTransfer = new KeyTranslationTransfer();
        $keyTranslationTransfer->setGlossaryKey($translationKey);

        $keyTranslationTransfer->setLocales([
            $glossaryTranslationTransfer->getLocaleName() => $glossaryTranslationTransfer->getTranslation(),
        ]);

        return $keyTranslationTransfer;
    }

}
