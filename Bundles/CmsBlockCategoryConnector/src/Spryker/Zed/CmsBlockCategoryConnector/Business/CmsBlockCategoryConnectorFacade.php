<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Business;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorBusinessFactory getFactory()
 */
class CmsBlockCategoryConnectorFacade extends AbstractFacade implements CmsBlockCategoryConnectorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function updateCmsBlockCategoryRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->getFactory()
            ->createCmsBlockCategoryWrite()
            ->updateCmsBlock($cmsBlockTransfer);
    }

}