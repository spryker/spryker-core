<?php


namespace Spryker\Zed\CmsBlockProductConnector\Business;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CmsBlockProductConnectorBusinessFactory getFactory()
 */
class CmsBlockProductConnectorFacade extends AbstractFacade implements CmsBlockProductConnectorFacadeInterface
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
    public function updateCmsBlockProductAbstractRelations(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->getFactory()
            ->createCmsBlockProductAbstractWriter()
            ->updateCmsBlockProductAbstractRelations($cmsBlockTransfer);
    }

}