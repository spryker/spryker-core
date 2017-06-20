<?php


namespace Spryker\Zed\CmsBlockProductConnector\Dependency\Facade;


use Spryker\Zed\Touch\Business\TouchFacadeInterface;

class CmsBlockProductConnectorToTouchFacadeBridge implements CmsBlockProductConnectorToTouchFacadeInterface
{

    /**
     * @var TouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @param TouchFacadeInterface $touchFacade
     */
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
