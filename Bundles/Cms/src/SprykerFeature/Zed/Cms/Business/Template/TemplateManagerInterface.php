<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Business\Template;

use Generated\Shared\Transfer\CmsTemplateTransfer;
use SprykerFeature\Zed\Cms\Business\Exception\MissingTemplateException;
use SprykerFeature\Zed\Cms\Business\Exception\TemplateExistsException;

interface TemplateManagerInterface
{

    /**
     * @param string $name
     * @param string $path
     *
     * @throws TemplateExistsException
     *
     * @return CmsTemplateTransfer
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
     * @param CmsTemplateTransfer $cmsTemplate
     *
     * @return CmsTemplateTransfer
     */
    public function saveTemplate(CmsTemplateTransfer $cmsTemplate);

    /**
     * @param int $idTemplate
     *
     * @throws MissingTemplateException
     *
     * @return CmsTemplateTransfer
     */
    public function getTemplateById($idTemplate);

    /**
     * @param string $path
     *
     * @throws MissingTemplateException
     *
     * @return CmsTemplateTransfer
     */
    public function getTemplateByPath($path);

    /**
     * @param string $cmsTemplateFolderPath
     *
     * @return bool
     */
    public function syncTemplate($cmsTemplateFolderPath);
}
