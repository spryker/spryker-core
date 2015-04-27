<?php

namespace SprykerFeature\Shared\SearchPage\Dependency;

interface PageAttributeTemplateInterface
{
    /**
     * @return int
     */
    public function getIdPageAttributeTemplate();

    /**
     * @param int $idPageAttributeTemplate
     *
     * @return $this
     */
    public function setIdPageAttributeTemplate($idPageAttributeTemplate);

    /**
     * @return string
     */
    public function getTemplateName();

    /**
     * @param string $templateName
     *
     * @return $this
     */
    public function setTemplateName($templateName);
}