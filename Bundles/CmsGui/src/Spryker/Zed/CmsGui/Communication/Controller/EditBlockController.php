<?php


namespace Spryker\Zed\CmsGui\Communication\Controller;


use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class EditBlockController extends AbstractController
{

    const URL_PARAM_ID_CMS_BLOCK = 'id-cms-block';
    const URL_PARAM_REDIRECT_URL = 'redirect-url';

    const REDIRECT_URL_DEFAULT = '/cms-gui/list-block/';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request)
    {
        $idCmsBlock = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_BLOCK));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        $this->getFactory()
            ->getCmsBlockFacade()
            ->activateById($idCmsBlock);

        $this->addSuccessMessage('Block successfully activated.');

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request)
    {
        $idCmsBlock = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_BLOCK));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        $this->getFactory()
            ->getCmsBlockFacade()
            ->deactivateById($idCmsBlock);

        $this->addSuccessMessage('Block successfully deactivated.');

        return $this->redirectResponse($redirectUrl);
    }

}