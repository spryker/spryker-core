<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Business\Template;

use Generated\Shared\Transfer\CmsTemplateTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Cms\Business\Exception\MissingTemplateException;
use SprykerFeature\Zed\Cms\Business\Exception\TemplateExistsException;
use SprykerFeature\Zed\Cms\CmsConfig;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainerInterface;
use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsTemplateTableMap;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplate;
use Symfony\Component\Finder\Finder;

class TemplateManager implements TemplateManagerInterface
{
    /**
     * @var CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @var CmsConfig
     */
    protected $config;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @param CmsQueryContainerInterface $cmsQueryContainer
     * @param LocatorLocatorInterface $locator
     * @param CmsConfig $config
     * @param Finder $finder
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        LocatorLocatorInterface $locator,
        CmsConfig $config,
        Finder $finder
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->locator = $locator;
        $this->config = $config;
        $this->finder = $finder;
    }

    /**
     * @param string $name
     * @param string $path
     *
     * @throws TemplateExistsException
     *
     * @return CmsTemplateTransfer
     */
    public function createTemplate($name, $path)
    {
        $this->checkTemplatePathDoesNotExist($path);

        $template = $this->locator->cms()->entitySpyCmsTemplate();
        $template
            ->setTemplateName($name)
            ->setTemplatePath($path)
            ->save()
        ;

        return $this->convertTemplateEntityToTransfer($template);
    }

    /**
     * @param string $path
     *
     * @throws TemplateExistsException
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
     * @param SpyCmsTemplate $template
     *
     * @return CmsTemplateTransfer
     */
    protected function convertTemplateEntityToTransfer(SpyCmsTemplate $template)
    {
        $transferTemplate = new \Generated\Shared\Transfer\CmsTemplateTransfer();
        $transferTemplate->fromArray($template->toArray());

        return $transferTemplate;
    }

    /**
     * @param CmsTemplateTransfer $cmsTemplate
     *
     * @return CmsTemplateTransfer
     */
    public function saveTemplate(CmsTemplateTransfer $cmsTemplate)
    {
        if (is_null($cmsTemplate->getIdCmsTemplate())) {
            return $this->createTemplateFromTransfer($cmsTemplate);
        } else {
            return $this->updateTemplateFromTransfer($cmsTemplate);
        }
    }

    /**
     * @param CmsTemplateTransfer $cmsTemplate
     *
     * @return CmsTemplateTransfer
     */
    protected function createTemplateFromTransfer(CmsTemplateTransfer $cmsTemplate)
    {
        $this->checkTemplatePathDoesNotExist($cmsTemplate->getTemplatePath());
        $templateEntity = $this->locator->cms()->entitySpyCmsTemplate();
        $templateEntity->fromArray($cmsTemplate->toArray());

        $templateEntity->save();

        $cmsTemplate->setIdCmsTemplate($templateEntity->getPrimaryKey());

        return $cmsTemplate;
    }

    /**
     * @param CmsTemplateTransfer $cmsTemplate
     *
     * @throws MissingTemplateException
     * @throws TemplateExistsException
     * @throws \Exception
     * @throws PropelException
     *
     * @return CmsTemplateTransfer
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
     * @throws MissingTemplateException
     *
     * @return CmsTemplateTransfer
     */
    public function getTemplateById($idTemplate)
    {
        $templateEntity = $this->getTemplateEntityById($idTemplate);

        return $this->convertTemplateEntityToTransfer($templateEntity);
    }

    /**
     * @param string $path
     *
     * @throws MissingTemplateException
     *
     * @return CmsTemplateTransfer
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
     * @throws MissingTemplateException
     *
     * @return SpyCmsTemplate
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
        $templateFolder = $this->config->getTemplateRealPath($cmsTemplateFolderPath);
        $isSynced = false;

        $this->finder->in($templateFolder)
            ->name('*.twig')
            ->depth('0')
        ;

        foreach ($this->finder->files() as $file) {
            $fileFullName = $file->getRelativePathname();

            try {
                $this->getTemplateByPath($cmsTemplateFolderPath . $fileFullName);
            } catch (MissingTemplateException $e) {
                $fileName = basename($templateFolder . $fileFullName, ".twig");
                $this->createTemplate($fileName, $cmsTemplateFolderPath . $fileFullName);
                $isSynced = true;
            }
        }

        return $isSynced;
    }
}
