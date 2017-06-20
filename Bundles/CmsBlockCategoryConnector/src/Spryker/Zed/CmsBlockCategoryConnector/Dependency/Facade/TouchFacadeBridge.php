<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade;


class TouchFacadeBridge implements TouchFacadeInterface
{

    /**
     * @var TouchFacadeInterface
     */
    protected $touchFacade;

    public function __construct($touchFacade)
    {
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param string $itemType
     * @param int $idCmsBlock
     *
     * @return bool
     */
    public function touchActive($itemType, $idCmsBlock)
    {
        return $this->touchFacade->touchActive($itemType, $idCmsBlock);
    }

    /**
     * @param string $itemType
     * @param int $idCmsBlock
     *
     * @return bool
     */
    public function touchDeleted($itemType, $idCmsBlock)
    {
        return $this->touchFacade->touchDeleted($itemType, $idCmsBlock);
    }

}