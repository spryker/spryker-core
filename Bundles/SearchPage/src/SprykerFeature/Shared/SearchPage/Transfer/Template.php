<?php

namespace SprykerFeature\Shared\SearchPage\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Shared\SearchPage\Dependency\TemplateInterface;

class Template extends AbstractTransfer implements TemplateInterface
{

    /**
     * @var int
     */
    protected $idPageElementTemplate = null;

    /**
     * @var string
     */
    protected $templateName = null;

    /**
     * @return int
     */
    public function getIdPageElementTemplate()
    {
        return $this->idPageElementTemplate;
    }

    /**
     * @param int $idPageElementTemplate
     *
     * @return $this
     */
    public function setIdPageElementTemplate($idPageElementTemplate)
    {
        $this->idPageElementTemplate = $idPageElementTemplate;
        $this->addModifiedProperty('idPageElementTemplate');

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
        $this->templateName = $templateName;
        $this->addModifiedProperty('templateName');

        return $this;
    }
}
