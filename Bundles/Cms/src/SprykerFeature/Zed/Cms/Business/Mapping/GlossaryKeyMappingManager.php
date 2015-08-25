<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Business\Mapping;

use Generated\Shared\Transfer\PageKeyMappingTransfer;
use Generated\Shared\Transfer\PageTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Cms\Business\Exception\MappingAmbiguousException;
use SprykerFeature\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException;
use SprykerFeature\Zed\Cms\Business\Page\PageManagerInterface;
use SprykerFeature\Zed\Cms\Business\Template\TemplateManagerInterface;
use SprykerFeature\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainerInterface;
use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsGlossaryKeyMappingTableMap;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsGlossaryKeyMapping;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingTranslationException;

class GlossaryKeyMappingManager implements GlossaryKeyMappingManagerInterface
{

    const GENERATED_GLOSSARY_KEY_PREFIX = 'generated.cms';

    /**
     * @var CmsToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var TemplateManagerInterface
     */
    protected $templateManager;

    /**
     * @var PageManagerInterface
     */
    protected $pageManager;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @param CmsToGlossaryInterface $glossaryFacade
     * @param CmsQueryContainerInterface $cmsQueryContainer
     * @param TemplateManagerInterface $templateManager
     * @param PageManagerInterface $pageManager
     * @param LocatorLocatorInterface $locator
     * @param ConnectionInterface $connection
     */
    public function __construct(CmsToGlossaryInterface $glossaryFacade, CmsQueryContainerInterface $cmsQueryContainer, TemplateManagerInterface $templateManager, PageManagerInterface $pageManager, LocatorLocatorInterface $locator, ConnectionInterface $connection)
    {
        $this->glossaryFacade = $glossaryFacade;
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->templateManager = $templateManager;
        $this->pageManager = $pageManager;
        $this->locator = $locator;
        $this->connection = $connection;
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     * @param array $data
     *
     * @throws MissingGlossaryKeyMappingException
     * @throws MissingTranslationException
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
     * @throws MissingGlossaryKeyMappingException
     *
     * @return PageKeyMappingTransfer
     */
    public function getPagePlaceholderMapping($idPage, $placeholder)
    {
        $glossaryKeyMappingEntity = $this->cmsQueryContainer->queryGlossaryKeyMapping($idPage, $placeholder)
            ->findOne()
        ;

        if (!$glossaryKeyMappingEntity) {
            throw new MissingGlossaryKeyMappingException(sprintf('Tried to translate a missing placeholder mapping: Placeholder %s on Page Id %s', $placeholder, $idPage));
        }

        return $this->convertMappingEntityToTransfer($glossaryKeyMappingEntity);
    }

    /**
     * @param PageKeyMappingTransfer $pageKeyMapping
     *
     * @return PageKeyMappingTransfer
     */
    public function savePageKeyMapping(PageKeyMappingTransfer $pageKeyMapping)
    {
        if (null === $pageKeyMapping->getIdCmsGlossaryKeyMapping()) {
            return $this->createPageKeyMapping($pageKeyMapping);
        } else {
            return $this->updatePageKeyMapping($pageKeyMapping);
        }
    }

    /**
     * @param PageKeyMappingTransfer $pageKeyMapping
     *
     * @return PageKeyMappingTransfer
     */
    public function savePageKeyMappingAndTouch(PageKeyMappingTransfer $pageKeyMapping)
    {
        $pageKeyMappingTransfer = $this->savePageKeyMapping($pageKeyMapping);

        $pageTransfer = (new PageTransfer())->setIdCmsPage($pageKeyMappingTransfer->getFkPage());
        $this->pageManager->touchPageActive($pageTransfer);

        return $pageKeyMappingTransfer;
    }

    /**
     * @param PageKeyMappingTransfer $pageKeyMapping
     *
     * @throws MappingAmbiguousException
     * @throws \Exception
     * @throws PropelException
     *
     * @return PageKeyMappingTransfer
     */
    protected function createPageKeyMapping(PageKeyMappingTransfer $pageKeyMapping)
    {
        $this->checkPagePlaceholderNotAmbiguous($pageKeyMapping->getFkPage(), $pageKeyMapping->getPlaceholder());

        $mappingEntity = $this->locator->cms()
            ->entitySpyCmsGlossaryKeyMapping()
        ;
        $mappingEntity->fromArray($pageKeyMapping->toArray());

        $mappingEntity->save();
        $pageKeyMapping->setIdCmsGlossaryKeyMapping($mappingEntity->getPrimaryKey());

        return $pageKeyMapping;
    }

    /**
     * @param PageKeyMappingTransfer $pageKeyMapping
     *
     * @throws MappingAmbiguousException
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     *
     * @return PageKeyMappingTransfer
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
     * @throws MappingAmbiguousException
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
            ->count()
        ;

        return $mappingCount > 0;
    }

    /**
     * @param int $idMapping
     *
     * @throws MissingGlossaryKeyMappingException
     *
     * @return SpyCmsGlossaryKeyMapping
     */
    protected function getGlossaryKeyMappingById($idMapping)
    {
        $mappingEntity = $this->cmsQueryContainer->queryGlossaryKeyMappingById($idMapping)
            ->findOne()
        ;
        if (!$mappingEntity) {
            throw new MissingGlossaryKeyMappingException(sprintf('Tried to retrieve a missing glossary key mapping with id %s', $idMapping));
        }

        return $mappingEntity;
    }

    /**
     * @param PageTransfer $page
     * @param string $placeholder
     * @param string $value
     *
     * @return PageKeyMappingTransfer
     */
    public function addPlaceholderText(PageTransfer $page, $placeholder, $value)
    {
        $template = $this->templateManager->getTemplateById($page->getFkTemplate());

        $keyName = $this->generateGlossaryKeyName($template->getTemplateName(), $placeholder);

        $this->connection->beginTransaction();

        $idKey = $this->glossaryFacade->createKey($keyName);
        $this->glossaryFacade->createTranslationForCurrentLocale($keyName, $value);

        $pageKeyMapping = new PageKeyMappingTransfer();
        $pageKeyMapping->setFkGlossaryKey($idKey);
        $pageKeyMapping->setPlaceholder($placeholder);
        $pageKeyMapping->setFkPage($page->getIdCmsPage());

        $pageKeyMapping = $this->savePageKeyMapping($pageKeyMapping);

        $this->connection->commit();

        return $pageKeyMapping;
    }

    /**
     * @param string $templateName
     * @param string $placeholder
     *
     * @return string
     */
    protected function generateGlossaryKeyName($templateName, $placeholder)
    {
        $keyName = self::GENERATED_GLOSSARY_KEY_PREFIX . '.';
        $keyName .= str_replace([' ', '.',], '-', $templateName) . '.';
        $keyName .= str_replace([' ', '.',], '-', $placeholder);

        $index = 0;

        $candidate = $keyName . $index;

        while ($this->glossaryFacade->hasKey($candidate)) {
            $candidate = $keyName . ++$index;
        }

        return $candidate;
    }

    /**
     * @param PageTransfer $page
     * @param string $placeholder
     *
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     *
     * @return bool
     */
    public function deletePageKeyMapping(PageTransfer $page, $placeholder)
    {
        $mappingQuery = $this->cmsQueryContainer->queryGlossaryKeyMapping($page->getIdCmsPage(), $placeholder);
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
            ->find()
        ;

        $pageTransfer = (new PageTransfer())->setIdCmsPage($idPage);

        foreach ($mappedGlossaries->getData() as $glossaryMapping) {
            $this->deletePageKeyMapping($pageTransfer, $glossaryMapping->getPlaceholder());
        }

        return true;
    }

    /**
     * @param SpyCmsGlossaryKeyMapping $mappingEntity
     *
     * @return PageKeyMappingTransfer
     */
    protected function convertMappingEntityToTransfer(SpyCmsGlossaryKeyMapping $mappingEntity)
    {
        $mappingTransfer = new PageKeyMappingTransfer();
        $mappingTransfer->fromArray($mappingEntity->toArray());

        return $mappingTransfer;
    }
}
