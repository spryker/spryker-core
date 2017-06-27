<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin;


use Spryker\Zed\CmsBlockGui\Communication\Plugin\CmsBlockViewPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface getFacade()
 */
class CmsBlockCategoryListViewPlugin extends AbstractPlugin implements CmsBlockViewPluginInterface
{

    /**
     * @param int $idCmsBlock
     * @param $idLocale
     *
     * @return string[]
     */
    public function getRenderedList($idCmsBlock, $idLocale)
    {
        return $this->getFacade()
            ->getRenderedCategoryList($idCmsBlock, $idLocale);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Category';
    }


}