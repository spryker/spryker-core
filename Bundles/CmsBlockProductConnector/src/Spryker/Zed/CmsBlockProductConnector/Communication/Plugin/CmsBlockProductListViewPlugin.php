<?php


namespace Spryker\Zed\CmsBlockProductConnector\Communication\Plugin;


use Spryker\Zed\CmsBlockGui\Communication\Plugin\CmsBlockViewPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface getFacade()
 */
class CmsBlockProductListViewPlugin extends AbstractPlugin implements CmsBlockViewPluginInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Product';
    }

    /**
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return \string[]
     */
    public function getRenderedList($idCmsBlock, $idLocale)
    {
        return $this->getFacade()
            ->getProductAbstractRenderedList($idCmsBlock, $idLocale);
    }

}