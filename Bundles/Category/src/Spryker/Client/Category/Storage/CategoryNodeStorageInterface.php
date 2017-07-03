<?php


namespace Spryker\Client\Category\Storage;


interface CategoryNodeStorageInterface
{

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return string
     */
    public function getTemplatePathByNodeId($idCategoryNode, $localeName);

}