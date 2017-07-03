<?php


namespace Spryker\Client\Category;


interface CategoryClientInterface
{
    /**
     * Specification:
     * - Resolve template for category node
     * - Return only the template path or NULL
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return string
     */
    public function getTemplatePathByNodeId($idCategoryNode, $localeName);

}