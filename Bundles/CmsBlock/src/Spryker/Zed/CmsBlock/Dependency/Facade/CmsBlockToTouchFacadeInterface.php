<?php


namespace Spryker\Zed\CmsBlock\Dependency\Facade;


interface CmsBlockToTouchFacadeInterface
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