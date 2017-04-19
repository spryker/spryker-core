<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Controller;

use Spryker\Zed\Cms\Business\Exception\CannotActivatePageException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class VersionPageController extends AbstractController
{

    const URL_PARAM_ID_CMS_PAGE = 'id-cms-page';
    const URL_PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function publishAction(Request $request)
    {
        $idCmsPage = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_PAGE));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        try {
            $this->getFactory()
                ->getCmsFacade()
                ->activatePage($idCmsPage);

            $this->getFactory()
                ->getCmsFacade()
                ->publishAndVersion($idCmsPage);

            $this->addSuccessMessage('Page successfully published.');

        } catch (CannotActivatePageException $exception) {
            $this->addErrorMessage('Cannot publish the CMS page, placeholders do not exist for this page Please go to "Edit Placeholders" and provide them.');
        } finally {
            return $this->redirectResponse($redirectUrl);
        }
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function discardAction(Request $request)
    {
        $idCmsPage = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_PAGE));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        $this->getFactory()
            ->getCmsFacade()
            ->revert($idCmsPage);

        $this->addSuccessMessage('Draft data successfully discarded.');

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @return void
     */
    public function rollbackAction()
    {
        $this->getFactory()->getCmsFacade()->rollback(1,2);
        dump('Revert');die;
    }
}
