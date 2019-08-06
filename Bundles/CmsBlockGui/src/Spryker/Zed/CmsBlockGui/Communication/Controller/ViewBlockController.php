<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\StoreTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsBlockGui\Communication\CmsBlockGuiCommunicationFactory getFactory()
 */
class ViewBlockController extends AbstractCmsBlockController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCmsBlock = $request->query->get(static::URL_PARAM_ID_CMS_BLOCK);
        $cmsBlockTransfer = $this->findCmsBlockById($idCmsBlock);

        if ($cmsBlockTransfer === null) {
            $this->addErrorMessage(static::MESSAGE_CMS_BLOCK_INVALID_ID_ERROR);

            return $this->getNotFoundBlockRedirect();
        }

        $cmsBlockGlossary = $this
            ->getFactory()
            ->getCmsBlockFacade()
            ->findGlossary($cmsBlockTransfer->getIdCmsBlock());

        $relatedStoreNames = $this->getStoreNames($cmsBlockTransfer->getStoreRelation()->getStores());

        return $this->viewResponse([
            'cmsBlock' => $cmsBlockTransfer,
            'cmsBlockGlossary' => $cmsBlockGlossary,
            'renderedPlugins' => $this->getRenderedViewPlugins($cmsBlockTransfer->getIdCmsBlock()),
            'relatedStoreNames' => $relatedStoreNames,
        ]);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return array
     */
    protected function getRenderedViewPlugins($idCmsBlock)
    {
        $viewPlugins = $this->getFactory()
            ->getCmsBlockViewPlugins();

        $currentLocale = $this->getFactory()
            ->getLocaleFacade()
            ->getCurrentLocale();

        $viewRenderedPlugins = [];

        foreach ($viewPlugins as $viewPlugin) {
            $viewRenderedPlugins[$viewPlugin->getName()] =
                $viewPlugin->getRenderedList($idCmsBlock, $currentLocale->getIdLocale());
        }

        return $viewRenderedPlugins;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $stores
     *
     * @return string[]
     */
    protected function getStoreNames(ArrayObject $stores)
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getName();
        }, $stores->getArrayCopy());
    }
}
