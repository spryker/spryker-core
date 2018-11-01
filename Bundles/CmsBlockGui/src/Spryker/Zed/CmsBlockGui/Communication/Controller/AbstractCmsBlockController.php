<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Controller;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Kernel\Exception\Controller\InvalidIdException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsBlockGui\Communication\CmsBlockGuiCommunicationFactory getFactory()
 */
abstract class AbstractCmsBlockController extends AbstractController
{
    public const URL_PARAM_ID_CMS_BLOCK = 'id-cms-block';
    public const REDIRECT_URL_DEFAULT = '/cms-block-gui/list-block';
    public const MESSAGE_CMS_BLOCK_INVALID_ID_ERROR = 'CMS block with provided ID doesn’t exist.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer|null
     */
    protected function findCmsBlockById(Request $request): ?CmsBlockTransfer
    {
        $idCmsBlock = $request->query->get(static::URL_PARAM_ID_CMS_BLOCK);

        try {
            $idCmsBlock = $this->castId($idCmsBlock);
        } catch (InvalidIdException $exception) {
            return null;
        }

        $cmsBlockTransfer = $this->getFactory()
            ->getCmsBlockFacade()
            ->findCmsBlockById($idCmsBlock);

        return $cmsBlockTransfer;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getNotFoundBlockRedirect(): RedirectResponse
    {
        $redirectUrl = Url::generate(static::REDIRECT_URL_DEFAULT)->build();

        return $this->redirectResponse($redirectUrl);
    }
}
