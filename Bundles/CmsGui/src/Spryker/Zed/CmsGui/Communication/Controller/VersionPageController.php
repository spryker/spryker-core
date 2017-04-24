<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Controller;

use Spryker\Zed\Cms\Business\Exception\CannotActivatePageException;
use Spryker\Zed\CmsGui\Communication\Form\Version\CmsVersionFormType;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class VersionPageController extends AbstractController
{

    const URL_PARAM_ID_CMS_PAGE = 'id-cms-page';
    const URL_PARAM_VERSION = 'version';
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

            $cmsVersionTransfer = $this->getFactory()
                ->getCmsFacade()
                ->publishAndVersion($idCmsPage);

            $this->addSuccessMessage(sprintf('Page with version %d successfully published.', $cmsVersionTransfer->getVersion()));

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
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function historyAction(Request $request)
    {
        $idCmsPage = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_PAGE));
        $version =  $request->query->get(static::URL_PARAM_VERSION);

        $cmsVersionFormDataProvider = $this->getFactory()
            ->createCmsVersionFormDataProvider();

        $versionForm = $this->getFactory()
            ->createCmsVersionForm($cmsVersionFormDataProvider, $idCmsPage, $version)
            ->handleRequest($request);

        if ($versionForm->isSubmitted()) {
            if ($versionForm->isValid()) {
                $cmsVersionData = $request->request->get(CmsVersionFormType::CMS_VERSION);
                $version = $this->castId($cmsVersionData['version']);
                if ($request->request->get('rollback') !== null) {
                    $cmsVersionTransfer =  $this->getFactory()->getCmsFacade()->rollback($idCmsPage, $version);
                    $this->addSuccessMessage(
                        sprintf('Rollback successfully applied and Page with version %d published.', $cmsVersionTransfer->getVersion())
                    );

                    return $this->redirectResponse('/cms-gui/version-page/history?id-cms-page='. $idCmsPage);
                }
            }
        }

        $cmsCurrentVersionTransfer = $this->getFactory()
            ->getCmsFacade()
            ->findLatestCmsVersionByIdCmsPage($idCmsPage);

        if ($cmsCurrentVersionTransfer === null) {
            throw new NotFoundHttpException(
                sprintf('Cms published page with id "%d" not found.', $idCmsPage)
            );
        }

        $cmsVersionDataHelper = $this->getFactory()->createCmsVersionDataHelper();

        $cmsCurrentPageTransfer = $cmsVersionDataHelper->extractCmsPageTransfer($cmsCurrentVersionTransfer);
        $cmsCurrentGlossaryTransfer = $cmsVersionDataHelper->extractCmsGlossaryPageTransfer($cmsCurrentVersionTransfer);
        $cmsTargetPage = null;
        $cmsTargetGlossary = null;

        if ($version !== null) {
            $cmsTargetVersionTransfer = $this->getFactory()
                ->getCmsFacade()
                ->findCmsVersionByIdCmsPageAndVersion($idCmsPage, $version);

            if ($cmsTargetVersionTransfer === null) {
                throw new NotFoundHttpException(
                    sprintf('Cms page with version "%d" not found.', $version)
                );
            }

            $cmsTargetPage = $cmsVersionDataHelper->extractCmsPageTransfer($cmsTargetVersionTransfer);
            $cmsTargetGlossary = $cmsVersionDataHelper->extractCmsGlossaryPageTransfer($cmsTargetVersionTransfer);
        }

        return [
            'cmsCurrentPage' => $cmsCurrentPageTransfer,
            'cmsCurrentGlossary' => $cmsCurrentGlossaryTransfer,
            'cmsTargetPage' => $cmsTargetPage,
            'cmsTargetGlossary' => $cmsTargetGlossary,
            'versionForm' => $versionForm->createView(),
            'cmsVersion' => $cmsCurrentVersionTransfer,
        ];
    }
}
