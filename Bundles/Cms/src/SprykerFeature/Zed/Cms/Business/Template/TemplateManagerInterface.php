<?php

namespace SprykerFeature\Zed\Cms\Business\Template;

use Generated\Shared\Transfer\CmsCmsTemplateTransfer;
use SprykerFeature\Zed\Cms\Business\Exception\MissingTemplateException;
use SprykerFeature\Zed\Cms\Business\Exception\TemplateExistsException;

interface TemplateManagerInterface
{
    /**
     * @param string $name
     * @param string $path
     *
     * @return CmsCmsTemplateTransfer
     * @throws TemplateExistsException
     */
    public function createTemplate($name, $path);

    /**
     * @param string $path
     *
     * @return bool
     */
    public function hasTemplatePath($path);

    /**
     * @param int $id
     *
     * @return bool
     */
    public function hasTemplateId($id);

    /**
     * @param CmsCmsTemplateTransfer $cmsTemplate
     *
     * @return CmsCmsTemplateTransfer
     */
    public function saveTemplate(CmsCmsTemplateTransfer $cmsTemplate);

    /**
     * @param int $idTemplate
     *
     * @return CmsCmsTemplateTransfer
     * @throws MissingTemplateException
     */
    public function getTemplateById($idTemplate);

    /**
     * @param string $path
     *
     * @return CmsCmsTemplateTransfer
     * @throws MissingTemplateException
     */
    public function getTemplateByPath($path);
}
