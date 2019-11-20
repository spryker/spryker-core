<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Generated\Shared\Transfer\CmsBlockTemplateTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplatePathExistsException;
use Spryker\Zed\CmsBlock\CmsBlockConfig;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;
use Symfony\Component\Finder\Finder;

class CmsBlockTemplateManager implements CmsBlockTemplateManagerInterface
{
    public const TEMPLATE_FILE_SUFFIX = '.twig';

    /**
     * @var \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateMapperInterface
     */
    protected $cmsBlockTemplateMapper;

    /**
     * @var \Spryker\Zed\CmsBlock\CmsBlockConfig
     */
    protected $config;

    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param \Spryker\Zed\CmsBlock\Business\Model\CmsBlockTemplateMapperInterface $cmsBlockTemplateMapper
     * @param \Spryker\Zed\CmsBlock\CmsBlockConfig $cmsBlockConfig
     * @param \Symfony\Component\Finder\Finder $finder
     */
    public function __construct(
        CmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockTemplateMapperInterface $cmsBlockTemplateMapper,
        CmsBlockConfig $cmsBlockConfig,
        Finder $finder
    ) {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->cmsBlockTemplateMapper = $cmsBlockTemplateMapper;
        $this->config = $cmsBlockConfig;
        $this->finder = $finder;
    }

    /**
     * @param string $name
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer
     */
    public function createTemplate(string $name, string $path): CmsBlockTemplateTransfer
    {
        $this->checkTemplatePathDoesNotExist($path);

        $template = new SpyCmsBlockTemplate();
        $template
            ->setTemplateName($name)
            ->setTemplatePath($path)
            ->save();

        return $this->cmsBlockTemplateMapper->mapTemplateEntityToTransfer($template);
    }

    /**
     * @param string $templatePath
     *
     * @return void
     */
    public function syncTemplate(string $templatePath): void
    {
        $templateFolders = $this->config->getTemplateRealPaths($templatePath);

        foreach ($templateFolders as $folder) {
            if (!file_exists($folder)) {
                continue;
            }

            $templatePaths = $this->createTemplateForTwigTemplates($templatePath, $folder);
            $this->createTemplates($templatePaths);
        }
    }

    /**
     * @param string $path
     *
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException
     *
     * @return void
     */
    public function checkTemplateFileExists(string $path): void
    {
        if (!$this->isTemplateFileExists($path)) {
            throw new CmsBlockTemplateNotFoundException(
                sprintf('Template file not found in "%s"', $path)
            );
        }
    }

    /**
     * @param int $idCmsBlockTemplate
     *
     * @return bool
     */
    public function hasTemplateFileById(int $idCmsBlockTemplate): bool
    {
        $templateEntity = $this->getTemplateById($idCmsBlockTemplate);

        return $this->isTemplateFileExists($templateEntity->getTemplatePath());
    }

    /**
     * @param int $idCmsBlockTemplate
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate|null
     */
    public function getTemplateById(int $idCmsBlockTemplate): ?SpyCmsBlockTemplate
    {
        return $this->cmsBlockQueryContainer
            ->queryTemplateById($idCmsBlockTemplate)
            ->findOne();
    }

    /**
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer|null
     */
    public function findTemplateByPath(string $path): ?CmsBlockTemplateTransfer
    {
        $spyCmsBlockTemplate = $this->cmsBlockQueryContainer
            ->queryTemplateByPath($path)
            ->findOne();

        if ($spyCmsBlockTemplate) {
            return $this->cmsBlockTemplateMapper->mapTemplateEntityToTransfer($spyCmsBlockTemplate);
        }

        return null;
    }

    /**
     * @param string $path
     *
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplatePathExistsException
     *
     * @return void
     */
    protected function checkTemplatePathDoesNotExist(string $path): void
    {
        if ($this->hasTemplatePath($path)) {
            throw new CmsBlockTemplatePathExistsException(
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
    protected function hasTemplatePath(string $path): bool
    {
        return $this->cmsBlockQueryContainer
            ->queryTemplateByPath($path)
            ->exists();
    }

    /**
     * @param string $realPath
     *
     * @return bool
     */
    protected function isTemplateFileExists(string $realPath): bool
    {
        $realPaths = $this->config->getTemplateRealPaths($realPath);

        foreach ($realPaths as $realPath) {
            if (file_exists($realPath)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $cmsTemplateFolderPath
     * @param string $folder
     *
     * @return array
     */
    protected function createTemplateForTwigTemplates(string $cmsTemplateFolderPath, string $folder): array
    {
        $templatePaths = [];
        $this->finder->in($folder)
            ->name('*' . static::TEMPLATE_FILE_SUFFIX)
            ->depth('0');

        foreach ($this->finder->files() as $file) {
            $fullFileName = $file->getRelativePathname();
            $exists = $this->cmsBlockQueryContainer->queryTemplateByPath($cmsTemplateFolderPath . $fullFileName)
                ->exists();

            if (!$exists) {
                $fileName = basename($folder . $fullFileName, static::TEMPLATE_FILE_SUFFIX);
                $absoluteFileName = $cmsTemplateFolderPath . $fullFileName;

                $templatePaths[$absoluteFileName] = $fileName;
            }
        }

        return $templatePaths;
    }

    /**
     * @param array $paths
     *
     * @return void
     */
    protected function createTemplates(array $paths): void
    {
        foreach ($paths as $path => $filename) {
            $this->createTemplate($filename, $path);
        }
    }
}
