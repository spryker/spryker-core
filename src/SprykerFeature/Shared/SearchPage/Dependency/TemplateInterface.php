<?php

namespace SprykerFeature\Shared\SearchPage\Dependency;

interface TemplateInterface
{
    /**
     * @return int
     */
    public function getIdPageElementTemplate();

    /**
     * @param int $idPageAttributeTemplate
     *
     * @return $this
     */
    public function setIdPageElementTemplate($idPageAttributeTemplate);

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

    /**
     * @param bool $includeNullValues
     * @param bool $recursive
     * @param bool $formatToUnderscore
     *
     * @return array
     */
    public function toArray($includeNullValues = true, $recursive = true, $formatToUnderscore = true);
}
