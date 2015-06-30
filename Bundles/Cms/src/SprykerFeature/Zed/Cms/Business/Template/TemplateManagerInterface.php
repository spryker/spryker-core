<?php
/**
 * (c) Spryker Systems GmbH copyright protected
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
     * @return CmsTemplateTransfer
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
     * @param CmsTemplateTransfer $cmsTemplate
     *
     * @return CmsTemplateTransfer
     */
    public function saveTemplate(CmsTemplateTransfer $cmsTemplate);

    /**
     * @param int $idTemplate
     *
     * @return CmsTemplateTransfer
     * @throws MissingTemplateException
     */
    public function getTemplateById($idTemplate);

    /**
     * @param string $path
     *
     * @return CmsTemplateTransfer
     * @throws MissingTemplateException
     */
    public function getTemplateByPath($path);
}
