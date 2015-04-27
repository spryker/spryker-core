<?php

namespace SprykerFeature\Shared\SearchPage\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Shared\SearchPage\Dependency\PageAttributeTemplateInterface;

class PageAttributeTemplate extends AbstractTransfer implements PageAttributeTemplateInterface
{

    /**
     * @var int
     */
    protected $idPageAttributeTemplate = null;

    /**
     * @var string
     */
    protected $templateName = null;

    /**
     * @return int
     */
    public function getIdPageAttributeTemplate()
    {
        return $this->idPageAttributeTemplate;
    }

    /**
     * @param int $idPageAttributeTemplate
     *
     * @return $this
     */
    public function setIdPageAttributeTemplate($idPageAttributeTemplate)
    {
        $this->idPageAttributeTemplate = $idPageAttributeTemplate;
        $this->addModifiedProperty('idPageAttributeTemplate');

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
