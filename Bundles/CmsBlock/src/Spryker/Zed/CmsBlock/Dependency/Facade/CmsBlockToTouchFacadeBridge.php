<?php

namespace Spryker\Zed\CmsBlock\Dependency\Facade;

use Spryker\Shared\CmsBlock\CmsBlockConstants;
use Spryker\Zed\Touch\Business\TouchFacadeInterface;

class CmsBlockToTouchFacadeBridge implements CmsBlockToTouchFacadeInterface
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