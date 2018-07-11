<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Template;

use Generated\Shared\Transfer\CmsTemplateTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsTemplate;
use Spryker\Zed\Cms\Business\Exception\MissingTemplateException;
use Spryker\Zed\Cms\Business\Exception\TemplateExistsException;
use Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Symfony\Component\Finder\Finder;

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
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\CmsConfig $config
     * @param \Symfony\Component\Finder\Finder $finder
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsConfig $config,
        Finder $finder
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->config = $config;
        $this->finder = $finder;
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
     * @param string $cmsTemplateFolderPath
     * @param string $folder
     *
     * @return bool
     */
    protected function findTwigFileAndCreateTemplate($cmsTemplateFolderPath, $folder)
    {
        $isTemplateCreated = false;
        $this->finder->in($folder)
            ->name('*.twig');

        foreach ($this->finder->files() as $file) {
            $fullFileName = $file->getRelativePathname();
            $cmsTemplateCount = $this->cmsQueryContainer->queryTemplateByPath($cmsTemplateFolderPath . $fullFileName)
                ->count();

            if ($cmsTemplateCount === 0) {
                $fileName = basename($folder . $fullFileName, '.twig');
                $this->createTemplate($fileName, $cmsTemplateFolderPath . $fullFileName);
                $isTemplateCreated = true;
            }
        }

        return $isTemplateCreated;
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
