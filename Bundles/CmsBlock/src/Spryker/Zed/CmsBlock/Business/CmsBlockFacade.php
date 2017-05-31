<?php

namespace Spryker\Zed\CmsBlock\Business;


use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method CmsBlockBusinessFactory getFactory()
 */
class CmsBlockFacade extends AbstractFacade implements CmsBlockFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function findCmsBlockById($idCmsBlock)
    {
        return $this->getFactory()
            ->createCmsBlockReader()
            ->findCmsBlockById($idCmsBlock);
    }

}