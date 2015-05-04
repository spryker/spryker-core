<?php

namespace SprykerFeature\Zed\Cms\Business\Template;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Propel\Runtime\Exception\PropelException;
use Generated\Shared\Transfer\CmsCmsTemplateTransfer;
use SprykerFeature\Zed\Cms\Business\Exception\MissingTemplateException;
use SprykerFeature\Zed\Cms\Business\Exception\TemplateExistsException;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainerInterface;
use SprykerFeature\Zed\Cms\Persistence\Propel\Map\SpyCmsTemplateTableMap;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplate;

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
     * @param CmsQueryContainerInterface $cmsQueryContainer
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        LocatorLocatorInterface $locator
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->locator = $locator;
    }

    /**
     * @param string $name
     * @param string $path
     *
     * @return CmsTemplate
     * @throws TemplateExistsException
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
     * @return CmsTemplate
     */
    protected function convertTemplateEntityToTransfer(SpyCmsTemplate $template)
    {
        $transferTemplate = new \Generated\Shared\Transfer\CmsCmsTemplateTransfer();
        $transferTemplate->fromArray($template->toArray());

        return $transferTemplate;
    }

    /**
     * @param CmsTemplate $cmsTemplate
     * @return CmsTemplate
     */
    public function saveTemplate(CmsTemplate $cmsTemplate)
    {
        if (is_null($cmsTemplate->getIdCmsTemplate())) {
            return $this->createTemplateFromTransfer($cmsTemplate);
        } else {
            return $this->updateTemplateFromTransfer($cmsTemplate);
        }
    }

    /**
     * @param CmsTemplate $cmsTemplate
     *
     * @return CmsTemplate
     */
    protected function createTemplateFromTransfer(CmsTemplate $cmsTemplate)
    {
        $this->checkTemplatePathDoesNotExist($cmsTemplate->getTemplatePath());
        $templateEntity = $this->locator->cms()->entitySpyCmsTemplate();
        $templateEntity->fromArray($cmsTemplate->toArray());

        $templateEntity->save();

        $cmsTemplate->setIdCmsTemplate($templateEntity->getPrimaryKey());

        return $cmsTemplate;
    }

    /**
     * @param CmsTemplate $cmsTemplate
     *
     * @return CmsTemplate
     * @throws MissingTemplateException
     * @throws TemplateExistsException
     * @throws \Exception
     * @throws PropelException
     */
    protected function updateTemplateFromTransfer(CmsTemplate $cmsTemplate)
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
     * @return CmsTemplate
     * @throws MissingTemplateException
     */
    public function getTemplateById($idTemplate)
    {
        $templateEntity = $this->getTemplateEntityById($idTemplate);

        return $this->convertTemplateEntityToTransfer($templateEntity);
    }

    /**
     * @param string $path
     *
     * @return CmsTemplate
     * @throws MissingTemplateException
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
     * @return SpyCmsTemplate
     * @throws MissingTemplateException
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
}
