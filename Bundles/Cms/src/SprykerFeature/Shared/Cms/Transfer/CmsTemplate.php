<?php

namespace SprykerFeature\Shared\Cms\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class CmsTemplate extends AbstractTransfer
{
    /**
     * @var int
     */
    protected $idCmsTemplate;

    /**
     * @var string
     */
    protected $templateName;

    /**
     * @var string
     */
    protected $templatePath;

    /**
     * @return int
     */
    public function getIdCmsTemplate()
    {
        return $this->idCmsTemplate;
    }

    /**
     * @param int $idCmsTemplate
     *
     * @return $this
     */
    public function setIdCmsTemplate($idCmsTemplate)
    {
        $this->addModifiedProperty('idCmsTemplate');
        $this->idCmsTemplate = $idCmsTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * @param string $templateName
     *
     * @return $this
     */
    public function setTemplateName($templateName)
    {
        $this->addModifiedProperty('templateName');
        $this->templateName = $templateName;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * @param string $templatePath
     *
     * @return $this
     */
    public function setTemplatePath($templatePath)
    {
        $this->addModifiedProperty('templatePath');
        $this->templatePath = $templatePath;

        return $this;
    }
}
