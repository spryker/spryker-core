<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Template;

use Generated\Shared\Transfer\CmsTemplateTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsTemplate;
use Spryker\Zed\Cms\Business\CmsBusinessFactory;
use Spryker\Zed\Cms\Business\Exception\MissingTemplateException;
use Spryker\Zed\Cms\Business\Exception\TemplateExistsException;
use Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\Persistence\CmsEntityManagerInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\Cms\Persistence\CmsRepositoryInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class TemplateManager implements TemplateManagerInterface
{
    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\CmsConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsRepositoryInterface
     */
    protected $cmsRepository;

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsEntityManagerInterface
     */
    protected $cmsEntityManager;

    /**
     * @var \Spryker\Zed\Cms\Business\CmsBusinessFactory
     */
    protected $cmsBusinessFactory;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\CmsConfig $config
     * @param \Spryker\Zed\Cms\Persistence\CmsRepositoryInterface $cmsRepository
     * @param \Spryker\Zed\Cms\Persistence\CmsEntityManagerInterface $cmsEntityManager
     * @param \Spryker\Zed\Cms\Business\CmsBusinessFactory $cmsBusinessFactory
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsConfig $config,
        CmsRepositoryInterface $cmsRepository,
        CmsEntityManagerInterface $cmsEntityManager,
        CmsBusinessFactory $cmsBusinessFactory
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->config = $config;
        $this->cmsRepository = $cmsRepository;
        $this->cmsEntityManager = $cmsEntityManager;
        $this->cmsBusinessFactory = $cmsBusinessFactory;
    }

    /**
     * @param string $name
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function createTemplate($name, $path)
    {
        $this->checkTemplatePathDoesNotExist($path);

        $template = new SpyCmsTemplate();
        $template
            ->setTemplateName($name)
            ->setTemplatePath($path)
            ->save();

        return $this->convertTemplateEntityToTransfer($template);
    }

    /**
     * @param string $path
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\TemplateExistsException
     *
     * @return void
     */
    protected function checkTemplatePathDoesNotExist($path)
    {
        if ($this->hasTemplatePath($path)) {
            throw new TemplateExistsException(
                sprintf(
                    'Tried to create a template with path %s, but it already exists',
                    $path
                )
            );
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function hasTemplatePath($path)
    {
        $templateCount = $this->cmsQueryContainer->queryTemplateByPath($path)->count();

        return $templateCount > 0;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function hasTemplateId($id)
    {
        $templateCount = $this->cmsQueryContainer->queryTemplateById($id)->count();

        return $templateCount > 0;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsTemplate $template
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    protected function convertTemplateEntityToTransfer(SpyCmsTemplate $template)
    {
        $transferTemplate = new CmsTemplateTransfer();
        $transferTemplate->fromArray($template->toArray());

        return $transferTemplate;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsTemplateTransfer $cmsTemplate
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function saveTemplate(CmsTemplateTransfer $cmsTemplate)
    {
        if ($cmsTemplate->getIdCmsTemplate() === null) {
            return $this->createTemplateFromTransfer($cmsTemplate);
        } else {
            return $this->updateTemplateFromTransfer($cmsTemplate);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsTemplateTransfer $cmsTemplate
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    protected function createTemplateFromTransfer(CmsTemplateTransfer $cmsTemplate)
    {
        $this->checkTemplatePathDoesNotExist($cmsTemplate->getTemplatePath());
        $templateEntity = new SpyCmsTemplate();
        $templateEntity->fromArray($cmsTemplate->toArray());

        $templateEntity->save();

        $cmsTemplate->setIdCmsTemplate($templateEntity->getPrimaryKey());

        return $cmsTemplate;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsTemplateTransfer $cmsTemplate
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    protected function updateTemplateFromTransfer(CmsTemplateTransfer $cmsTemplate)
    {
        $templateEntity = $this->getTemplateEntityById($cmsTemplate->getIdCmsTemplate());
        $templateEntity->fromArray($cmsTemplate->toArray());

        if (!$templateEntity->isModified()) {
            return $cmsTemplate;
        }

        if ($templateEntity->isColumnModified(SpyCmsTemplateTableMap::COL_TEMPLATE_PATH)) {
            $this->checkTemplatePathDoesNotExist($cmsTemplate->getTemplatePath());
        }

        $templateEntity->save();

        return $cmsTemplate;
    }

    /**
     * @param int $idTemplate
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function getTemplateById($idTemplate)
    {
        $templateEntity = $this->getTemplateEntityById($idTemplate);

        return $this->convertTemplateEntityToTransfer($templateEntity);
    }

    /**
     * @param string $path
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingTemplateException
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function getTemplateByPath($path)
    {
        $templateEntity = $this->cmsQueryContainer->queryTemplateByPath($path)->findOne();
        if (!$templateEntity) {
            throw new MissingTemplateException(
                sprintf(
                    'Tried to retrieve a missing template with path %s',
                    $path
                )
            );
        }

        return $this->convertTemplateEntityToTransfer($templateEntity);
    }

    /**
     * @param int $idTemplate
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingTemplateException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplate
     */
    protected function getTemplateEntityById($idTemplate)
    {
        $templateEntity = $this->cmsQueryContainer->queryTemplateById($idTemplate)->findOne();
        if (!$templateEntity) {
            throw new MissingTemplateException(
                sprintf(
                    'Tried to retrieve a missing template with id %s',
                    $idTemplate
                )
            );
        }

        return $templateEntity;
    }

    /**
     * @param string $cmsTemplateFolderPath
     *
     * @return bool
     */
    public function syncTemplate($cmsTemplateFolderPath)
    {
        $templateFolders = $this->config->getTemplateRealPaths($cmsTemplateFolderPath);
        $isSynced = false;

        foreach ($templateFolders as $folder) {
            if (!$this->fileExists($folder)) {
                continue;
            }

            $isSynced = $this->findTwigFileAndCreateTemplate($cmsTemplateFolderPath, $folder);
        }

        return $isSynced;
    }

    /**
     * @param string $path
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException
     *
     * @return void
     */
    public function checkTemplateFileExists($path)
    {
        if (!$this->isTemplateFileExists($path)) {
            throw new TemplateFileNotFoundException(
                sprintf('Template file not found in "%s"', $path)
            );
        }
    }

    /**
     * @param string $realPath
     *
     * @return bool
     */
    protected function isTemplateFileExists($realPath)
    {
        $realPaths = $this->config->getTemplateRealPaths($realPath);

        foreach ($realPaths as $realPath) {
            if ($this->fileExists($realPath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $cmsTemplateEntityPrefix
     * @param string $cmsFolderPath
     *
     * @return bool
     */
    protected function findTwigFileAndCreateTemplate($cmsTemplateEntityPrefix, $cmsFolderPath)
    {
        $cmsTemplateFolders = $this->createFinderInstanceUsingCmsBusinessFactory()
            ->in($cmsFolderPath)
            ->directories();

        $isTemplateCreated = false;

        foreach ($cmsTemplateFolders as $cmsTemplateFolder) {
            $cmsTemplateEntityCreated = $this->processSingleCmsTemplateFolder(
                $cmsTemplateEntityPrefix,
                $cmsTemplateFolder
            );

            if ($cmsTemplateEntityCreated) {
                $isTemplateCreated = true;
            }
        }

        $cmsTemplateFolderFilesPaths = $this->getCmsTemplateFolderFilePaths(
            $cmsTemplateEntityPrefix,
            $cmsFolderPath,
            $cmsTemplateFolders
        );

        $this->deleteNonExistingCmsTemplateEntities($cmsTemplateFolderFilesPaths);

        return $isTemplateCreated;
    }

    /**
     * @param string[] $cmsTemplateFolderPaths
     *
     * @return void
     */
    protected function deleteNonExistingCmsTemplateEntities(array $cmsTemplateFolderPaths): void
    {
        $storedCmsTemplateEntitiesPaths = $this->cmsRepository->findAllCmsTemplatePaths();
        $nonExistingEntityPaths = array_diff($storedCmsTemplateEntitiesPaths, $cmsTemplateFolderPaths);

        $this->cmsEntityManager->deleteNonExistingCmsTemplateEntitiesByPaths($nonExistingEntityPaths);
    }

    /**
     * @param string $cmsTemplateEntityPrefix
     * @param string $cmsFolderPath
     * @param \Symfony\Component\Finder\Finder $cmsTemplateFolder
     *
     * @return string[]
     */
    protected function getCmsTemplateFolderFilePaths(
        string $cmsTemplateEntityPrefix,
        string $cmsFolderPath,
        Finder $cmsTemplateFolder
    ): array {
        $cmsTemplateFolderFilePaths = [];

        $cmsTemplateFolderFiles = $cmsTemplateFolder->files();
        foreach ($cmsTemplateFolderFiles as $cmsTemplateFolderFile) {
            $cmsTemplateFolderFilePaths[] = $this->getSingleCmsTemplateFolderFilePath(
                $cmsFolderPath,
                $cmsTemplateEntityPrefix,
                $cmsTemplateFolderFile
            );
        }

        return $cmsTemplateFolderFilePaths;
    }

    /**
     * @param string $cmsFolderPath
     * @param string $cmsTemplateEntityPrefix
     * @param \Symfony\Component\Finder\SplFileInfo $cmsTemplateFolderFile
     *
     * @return string
     */
    protected function getSingleCmsTemplateFolderFilePath(
        string $cmsFolderPath,
        string $cmsTemplateEntityPrefix,
        SplFileInfo $cmsTemplateFolderFile
    ): string {
        return str_replace(
            $cmsFolderPath,
            $cmsTemplateEntityPrefix,
            $cmsTemplateFolderFile->getRealPath()
        );
    }

    /**
     * @param string $cmsTemplateEntityPrefix
     * @param \Symfony\Component\Finder\SplFileInfo $cmsTemplateFolderInfo
     *
     * @return bool
     */
    protected function processSingleCmsTemplateFolder(
        string $cmsTemplateEntityPrefix,
        SplFileInfo $cmsTemplateFolderInfo
    ): bool {
        $isTemplateCreated = false;

        $cmsTemplateFolderPath = $cmsTemplateFolderInfo->getRealPath();
        $cmsTemplateFiles = $this->findCmsTemplateFilesInFolder(
            $cmsTemplateFolderPath
        );

        $cmsTemplateEntityPrefix = $cmsTemplateEntityPrefix
            . $cmsTemplateFolderInfo->getRelativePathname()
            . DIRECTORY_SEPARATOR;

        foreach ($cmsTemplateFiles as $cmsTemplateFile) {
            $cmsTemplateEntityCreated = $this->processSingleCmsTemplateFile(
                $cmsTemplateFile,
                $cmsTemplateEntityPrefix
            );

            if ($cmsTemplateEntityCreated) {
                $isTemplateCreated = true;
            }
        }

        return $isTemplateCreated;
    }

    /**
     * @param string $cmsTemplateFolderPath
     *
     * @return \Symfony\Component\Finder\Finder
     */
    protected function findCmsTemplateFilesInFolder(string $cmsTemplateFolderPath): Finder
    {
        return $this->createFinderInstanceUsingCmsBusinessFactory()
            ->in($cmsTemplateFolderPath)
            ->name('*.twig')
            ->depth('0')
            ->files();
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $cmsTemplateFileInfo
     * @param string $cmsFilePathPrefix
     *
     * @return bool
     */
    protected function processSingleCmsTemplateFile(
        SplFileInfo $cmsTemplateFileInfo,
        string $cmsFilePathPrefix
    ): bool {
        $cmsTemplateEntityCreated = false;

        $cmsTemplateFileName = $cmsTemplateFileInfo->getRelativePathname();
        $cmsTemplateFilePath = $cmsFilePathPrefix . $cmsTemplateFileName;

        if (!$this->isCmsTemplateEntityAlreadyExist($cmsTemplateFilePath)) {
            $this->createCmsTemplateEntity(
                $cmsTemplateFileName,
                $cmsTemplateFilePath
            );
            $cmsTemplateEntityCreated = true;
        }

        return $cmsTemplateEntityCreated;
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function createFinderInstanceUsingCmsBusinessFactory(): Finder
    {
        return $this->cmsBusinessFactory->createFinder();
    }

    /**
     * @param string $cmsTemplateFilePath
     *
     * @return bool
     */
    protected function isCmsTemplateEntityAlreadyExist(string $cmsTemplateFilePath): bool
    {
        $cmsTemplateEntity = $this->cmsRepository
            ->findCmsTemplateByPath($cmsTemplateFilePath);

        return $cmsTemplateEntity !== null;
    }

    /**
     * @param string $cmsTemplateFileName
     * @param string $cmsTemplateFilePath
     *
     * @return void
     */
    protected function createCmsTemplateEntity(string $cmsTemplateFileName, string $cmsTemplateFilePath): void
    {
        $cmsTemplateEntityName = basename($cmsTemplateFileName, '.twig');
        $this->createTemplate($cmsTemplateEntityName, $cmsTemplateFilePath);
    }

    /**
     * @param string $templateFile
     *
     * @return bool
     */
    protected function fileExists($templateFile)
    {
        return file_exists($templateFile);
    }
}
