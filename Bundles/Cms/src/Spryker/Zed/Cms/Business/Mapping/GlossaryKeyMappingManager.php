<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Mapping;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Cms\Business\Exception\MappingAmbiguousException;
use Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException;
use Spryker\Zed\Cms\Business\Page\PageManagerInterface;
use Spryker\Zed\Cms\Business\Template\TemplateManagerInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class GlossaryKeyMappingManager implements GlossaryKeyMappingManagerInterface
{
    const GENERATED_GLOSSARY_KEY_PREFIX = 'generated.cms';

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Business\Template\TemplateManagerInterface
     */
    protected $templateManager;

    /**
     * @var \Spryker\Zed\Cms\Business\Page\PageManagerInterface
     */
    protected $pageManager;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface $glossaryFacade
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Business\Template\TemplateManagerInterface $templateManager
     * @param \Spryker\Zed\Cms\Business\Page\PageManagerInterface $pageManager
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     */
    public function __construct(
        CmsToGlossaryInterface $glossaryFacade,
        CmsQueryContainerInterface $cmsQueryContainer,
        TemplateManagerInterface $templateManager,
        PageManagerInterface $pageManager,
        ConnectionInterface $connection
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->templateManager = $templateManager;
        $this->pageManager = $pageManager;
        $this->connection = $connection;
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     * @param array $data
     *
     * @return string
     */
    public function translatePlaceholder($idPage, $placeholder, array $data = [])
    {
        $glossaryKeyMapping = $this->getPagePlaceholderMapping($idPage, $placeholder);

        return $this->glossaryFacade->translateByKeyId($glossaryKeyMapping->getFkGlossaryKey(), $data);
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function getPagePlaceholderMapping($idPage, $placeholder)
    {
        $glossaryKeyMappingEntity = $this->cmsQueryContainer->queryGlossaryKeyMapping($idPage, $placeholder)
            ->findOne();

        if (!$glossaryKeyMappingEntity) {
            throw new MissingGlossaryKeyMappingException(sprintf('Tried to translate a missing placeholder mapping: Placeholder %s on Page Id %s', $placeholder, $idPage));
        }

        return $this->convertMappingEntityToTransfer($glossaryKeyMappingEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\PageKeyMappingTransfer $pageKeyMappingTransfer
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function savePageKeyMapping(PageKeyMappingTransfer $pageKeyMappingTransfer)
    {
        if ($pageKeyMappingTransfer->getIdCmsGlossaryKeyMapping() === null) {
            return $this->createPageKeyMapping($pageKeyMappingTransfer);
        } else {
            return $this->updatePageKeyMapping($pageKeyMappingTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PageKeyMappingTransfer $pageKeyMappingTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function savePageKeyMappingAndTouch(PageKeyMappingTransfer $pageKeyMappingTransfer, ?LocaleTransfer $localeTransfer = null)
    {
        $savedPageKeyMappingTransfer = $this->savePageKeyMapping($pageKeyMappingTransfer);

        $pageTransfer = (new PageTransfer())->setIdCmsPage($savedPageKeyMappingTransfer->getFkPage());
        $this->pageManager->touchPageActive($pageTransfer, $localeTransfer);

        return $savedPageKeyMappingTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PageKeyMappingTransfer $pageKeyMapping
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    protected function createPageKeyMapping(PageKeyMappingTransfer $pageKeyMapping)
    {
        $this->checkPagePlaceholderNotAmbiguous($pageKeyMapping->getFkPage(), $pageKeyMapping->getPlaceholder());

        $mappingEntity = new SpyCmsGlossaryKeyMapping();
        $mappingEntity->fromArray($pageKeyMapping->toArray());

        $mappingEntity->save();
        $pageKeyMapping->setIdCmsGlossaryKeyMapping($mappingEntity->getPrimaryKey());

        return $pageKeyMapping;
    }

    /**
     * @param \Generated\Shared\Transfer\PageKeyMappingTransfer $pageKeyMapping
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    protected function updatePageKeyMapping(PageKeyMappingTransfer $pageKeyMapping)
    {
        $mappingEntity = $this->getGlossaryKeyMappingById($pageKeyMapping->getIdCmsGlossaryKeyMapping());
        $mappingEntity->fromArray($pageKeyMapping->toArray());

        if (!$mappingEntity->isModified()) {
            return $pageKeyMapping;
        }

        $isPlaceholderModified = $mappingEntity->isColumnModified(SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER);
        $isPageIdModified = $mappingEntity->isColumnModified(SpyCmsGlossaryKeyMappingTableMap::COL_FK_PAGE);

        if ($isPlaceholderModified || $isPageIdModified) {
            $this->checkPagePlaceholderNotAmbiguous($pageKeyMapping->getFkPage(), $pageKeyMapping->getPlaceholder());
        }

        $mappingEntity->save();

        return $pageKeyMapping;
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
        $mappingCount = $this->cmsQueryContainer->queryGlossaryKeyMapping($idPage, $placeholder)
            ->count();

        return $mappingCount > 0;
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
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $placeholder
     * @param string $value
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     * @param bool $autoGlossaryKeyIncrement
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    public function addPlaceholderText(PageTransfer $pageTransfer, $placeholder, $value, ?LocaleTransfer $localeTransfer = null, $autoGlossaryKeyIncrement = true)
    {
        $template = $this->templateManager->getTemplateById($pageTransfer->getFkTemplate());

        $uniquePlaceholder = $placeholder . '-' . $pageTransfer->getIdCmsPage();
        $keyName = $this->generateGlossaryKeyName($template->getTemplateName(), $uniquePlaceholder, $autoGlossaryKeyIncrement);

        $this->connection->beginTransaction();

        $pageKeyMapping = $this->createGlossaryPageKeyMapping($pageTransfer, $placeholder, $keyName, $value, $localeTransfer);

        if (!$this->hasPagePlaceholderMapping($pageTransfer->getIdCmsPage(), $placeholder)) {
            $pageKeyMapping = $this->savePageKeyMapping($pageKeyMapping);
        }

        $this->connection->commit();

        return $pageKeyMapping;
    }

    /**
     * @param string $templateName
     * @param string $placeholder
     * @param bool $autoIncrement
     *
     * @return string
     */
    public function generateGlossaryKeyName($templateName, $placeholder, $autoIncrement = true)
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
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $placeholder
     *
     * @return bool
     */
    public function deletePageKeyMapping(PageTransfer $pageTransfer, $placeholder)
    {
        $mappingQuery = $this->cmsQueryContainer->queryGlossaryKeyMapping($pageTransfer->getIdCmsPage(), $placeholder);
        $mappingQuery->delete();

        return true;
    }

    /**
     * @param int $idPage
     *
     * @return bool
     */
    public function deleteGlossaryKeysByIdPage($idPage)
    {
        $mappedGlossaries = $this->cmsQueryContainer->queryGlossaryKeyMappingsByPageId($idPage)
            ->find();

        $pageTransfer = (new PageTransfer())->setIdCmsPage($idPage);

        foreach ($mappedGlossaries->getData() as $glossaryMapping) {
            $this->deletePageKeyMapping($pageTransfer, $glossaryMapping->getPlaceholder());
        }

        return true;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping $mappingEntity
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    protected function convertMappingEntityToTransfer(SpyCmsGlossaryKeyMapping $mappingEntity)
    {
        $mappingTransfer = new PageKeyMappingTransfer();
        $mappingTransfer->fromArray($mappingEntity->toArray(), true);

        return $mappingTransfer;
    }

    /**
     * @param string $keyName
     * @param string $value
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    protected function createGlossaryTranslation($keyName, $value, ?LocaleTransfer $localeTransfer = null)
    {
        if ($localeTransfer !== null) {
            $this->glossaryFacade->createAndTouchTranslation($keyName, $localeTransfer, $value);
        } else {
            $this->glossaryFacade->createTranslationForCurrentLocale($keyName, $value);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     * @param string $placeholder
     * @param int $idKey
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    protected function createPageKeyMappingTransfer(PageTransfer $page, $placeholder, $idKey)
    {
        $pageKeyMapping = new PageKeyMappingTransfer();
        $pageKeyMapping->setFkGlossaryKey($idKey);
        $pageKeyMapping->setPlaceholder($placeholder);
        $pageKeyMapping->setFkPage($page->getIdCmsPage());

        return $pageKeyMapping;
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $page
     * @param string $placeholder
     * @param string $keyName
     * @param string $value
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageKeyMappingTransfer
     */
    protected function createGlossaryPageKeyMapping(PageTransfer $page, $placeholder, $keyName, $value, ?LocaleTransfer $localeTransfer = null)
    {
        $idKey = $this->glossaryFacade->getOrCreateKey($keyName);
        $this->createGlossaryTranslation($keyName, $value, $localeTransfer);
        $pageKeyMapping = $this->createPageKeyMappingTransfer($page, $placeholder, $idKey);

        return $pageKeyMapping;
    }
}
