<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade;


interface TouchFacadeInterface
{
    /**
     * @param string $itemType
     * @param int $idCmsBlock
     *
     * @return bool
     */
    public function touchActive($itemType, $idCmsBlock);

    /**
     * @param string $itemType
     * @param int $idCmsBlock
     *
     * @return bool
     */
    public function touchDeleted($itemType, $idCmsBlock);

}