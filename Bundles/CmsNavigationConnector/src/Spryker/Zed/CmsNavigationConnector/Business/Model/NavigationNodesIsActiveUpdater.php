<?php

namespace Spryker\Zed\CmsNavigationConnector\Business\Model;

use Spryker\Zed\CmsNavigationConnector\Dependency\Facade\CmsNavigationConnectorToNavigationFacadeInterface;

class NavigationNodesIsActiveUpdater implements NavigationNodesIsActiveUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CmsNavigationConnector\Dependency\Facade\CmsNavigationConnectorToNavigationFacadeInterface
     */
    protected $navigationFacade;

    /**
     * @var \Spryker\Zed\CmsNavigationConnector\Business\Model\NavigationNodeReaderInterface
     */
    protected $navigationNodeReader;

    /**
     * @param \Spryker\Zed\CmsNavigationConnector\Dependency\Facade\CmsNavigationConnectorToNavigationFacadeInterface $navigationFacade
     * @param \Spryker\Zed\CmsNavigationConnector\Business\Model\NavigationNodeReaderInterface $navigationNodeReader
     */
    public function __construct(CmsNavigationConnectorToNavigationFacadeInterface $navigationFacade, NavigationNodeReaderInterface $navigationNodeReader)
    {
        $this->navigationFacade = $navigationFacade;
        $this->navigationNodeReader = $navigationNodeReader;
    }

    /**
     * @param int $idCmsPage
     * @param bool $isActive
     */
    public function updateCmsPageNavigationNodes($idCmsPage, $isActive)
    {
        $navigationNodes = $this->navigationNodeReader->getNavigationNodesFromCmsPageId($idCmsPage);
        foreach($navigationNodes as $navigationNode) {
            $navigationNode->setIsActive($isActive);
            $this->navigationFacade->updateNavigationNode($navigationNode);
        }
    }
}
