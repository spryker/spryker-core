<?php

namespace SprykerFeature\Zed\Cms\Business\Mapping;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsPageKeyMappingTransfer;
use SprykerFeature\Zed\Cms\Business\Exception\MappingAmbiguousException;
use SprykerFeature\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException;
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
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param CmsToGlossaryInterface $glossaryFacade
     * @param CmsQueryContainerInterface $cmsQueryContainer
     * @param TemplateManagerInterface $templateManager
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        CmsToGlossaryInterface $glossaryFacade,
        CmsQueryContainerInterface $cmsQueryContainer,
        TemplateManagerInterface $templateManager,
        LocatorLocatorInterface $locator
    ) {
        $this->glossaryFacade = $glossaryFacade;
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->templateManager = $templateManager;
        $this->locator = $locator;
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     * @param array $data
     *
     * @return string
     * @throws MissingGlossaryKeyMappingException
     * @throws MissingTranslationException
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
     * @return PageKeyMapping
     * @throws MissingGlossaryKeyMappingException
     */
    public function getPagePlaceholderMapping($idPage, $placeholder)
    {
        $glossaryKeyMapping = $this->cmsQueryContainer->queryGlossaryKeyMapping($idPage, $placeholder)->findOne();

        if (!$glossaryKeyMapping) {
            throw new MissingGlossaryKeyMappingException(
                sprintf(
                    'Tried to translate a missing placeholder mapping: Placeholder %s on Page Id %s',
                    $placeholder,
                    $idPage
                )
            );
        }

        return $this->convertMappingEntityToTransfer($glossaryKeyMapping);
    }

    /**
     * @param PageKeyMapping $pageKeyMapping
     *
     * @return PageKeyMapping
     */
    public function savePageKeyMapping(PageKeyMapping $pageKeyMapping)
    {
        if (is_null($pageKeyMapping->getIdCmsGlossaryKeyMapping())) {
            return $this->createPageKeyMapping($pageKeyMapping);
        } else {
            return $this->updatePageKeyMapping($pageKeyMapping);
        }
    }

    /**
     * @param PageKeyMapping $pageKeyMapping
     *
     * @return PageKeyMapping
     * @throws MappingAmbiguousException
     * @throws \Exception
     * @throws PropelException
     */
    protected function createPageKeyMapping(PageKeyMapping $pageKeyMapping)
    {
        $this->checkPagePlaceholderNotAmbiguous($pageKeyMapping->getFkPage(), $pageKeyMapping->getPlaceholder());

        $mappingEntity = $this->locator->cms()->entitySpyCmsGlossaryKeyMapping();
        $mappingEntity->fromArray($pageKeyMapping->toArray());

        $mappingEntity->save();
        $pageKeyMapping->setIdCmsGlossaryKeyMapping($mappingEntity->getPrimaryKey());

        return $pageKeyMapping;
    }

    /**
     * @param PageKeyMapping $pageKeyMapping
     *
     * @return PageKeyMapping
     * @throws MappingAmbiguousException
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     */
    protected function updatePageKeyMapping(PageKeyMapping $pageKeyMapping)
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
            throw new MappingAmbiguousException(
                sprintf(
                    'Tried to create an ambiguous mapping for placeholder %s on page %s',
                    $placeholder,
                    $idPage
                )
            );
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
        $mappingCount = $this->cmsQueryContainer->queryGlossaryKeyMapping($idPage, $placeholder)->count();

        return $mappingCount > 0;
    }

    /**
     * @param int $idMapping
     *
     * @return SpyCmsGlossaryKeyMapping
     * @throws MissingGlossaryKeyMappingException
     */
    protected function getGlossaryKeyMappingById($idMapping)
    {
        $mapping = $this->cmsQueryContainer->queryGlossaryKeyMappingById($idMapping)->findOne();
        if (!$mapping) {
            throw new MissingGlossaryKeyMappingException(
                sprintf(
                    'Tried to retrieve a missing glossary key mapping with id %s',
                    $idMapping
                )
            );
        }

        return $mapping;
    }

    /**
     * @param Page $page
     * @param string $placeholder
     * @param string $value
     *
     * @return PageKeyMapping
     */
    public function addPlaceholderText(Page $page, $placeholder, $value)
    {
        $template = $this->templateManager->getTemplateById($page->getFkTemplate());

        $keyName = $this->generateGlossaryKeyName($template->getTemplateName(), $placeholder);

        Propel::getConnection()->beginTransaction();

        $idKey = $this->glossaryFacade->createKey($keyName);
        $this->glossaryFacade->createTranslationForCurrentLocale($keyName, $value);

        $pageKeyMapping = new \Generated\Shared\Transfer\CmsPageKeyMappingTransfer();
        $pageKeyMapping->setFkGlossaryKey($idKey);
        $pageKeyMapping->setPlaceholder($placeholder);
        $pageKeyMapping->setFkPage($page->getIdCmsPage());

        $pageKeyMapping = $this->savePageKeyMapping($pageKeyMapping);

        Propel::getConnection()->commit();

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
        $keyName .= str_replace([' ', '.'], '-', $templateName) . '.';
        $keyName .= str_replace([' ', '.'], '-', $placeholder);

        $index = 0;

        $candidate = $keyName . $index;

        while ($this->glossaryFacade->hasKey($candidate)) {
            $candidate = $keyName . ++$index;
        }

        return $candidate;
    }

    /**
     * @param Page $page
     * @param string $placeholder
     *
     * @return bool
     * @throws MissingGlossaryKeyMappingException
     * @throws \Exception
     * @throws PropelException
     */
    public function deletePageKeyMapping(Page $page, $placeholder)
    {
        $mappingQuery = $this->cmsQueryContainer->queryGlossaryKeyMapping($page->getIdCmsPage(), $placeholder);
        $mappingQuery->delete();

        return true;
    }

    /**
     * @param SpyCmsGlossaryKeyMapping $mappingEntity
     *
     * @return PageKeyMapping
     */
    protected function convertMappingEntityToTransfer(SpyCmsGlossaryKeyMapping $mappingEntity)
    {
        $mappingTransfer = new \Generated\Shared\Transfer\CmsPageKeyMappingTransfer();
        $mappingTransfer->fromArray($mappingEntity->toArray());

        return $mappingTransfer;
    }
}
