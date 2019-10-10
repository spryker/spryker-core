<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Controller;

use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Cms\Business\Exception\CannotActivatePageException;
use Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException;
use Spryker\Zed\CmsGui\Communication\Form\Version\CmsVersionFormType;
use Spryker\Zed\CmsGui\Communication\Mapper\CmsVersionMapperInterface;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class VersionPageController extends AbstractController
{
    public const URL_PARAM_ID_CMS_PAGE = 'id-cms-page';
    public const URL_PARAM_VERSION = 'version';
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
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
                ->publishWithVersion($idCmsPage);

            $this->addSuccessMessage('Page with version %d successfully published.', ['%d' => $cmsVersionTransfer->getVersion()]);
        } catch (CannotActivatePageException $exception) {
            $this->addErrorMessage('Cannot publish the CMS page. Please fill in all placeholders for this page.');

            return $this->redirectResponseExternal($request->headers->get('referer'));
        }

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function historyAction(Request $request)
    {
        $idCmsPage = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_PAGE));
        $version = $request->query->get(static::URL_PARAM_VERSION);
        $redirect = null;

        $cmsVersionFormDataProvider = $this->getFactory()
            ->createCmsVersionFormDataProvider();

        $versionForm = $this->getFactory()
            ->getCmsVersionForm($cmsVersionFormDataProvider, $idCmsPage, $version)
            ->handleRequest($request);

        if ($versionForm->isSubmitted() && $versionForm->isValid()) {
            $cmsVersionData = $request->request->get(CmsVersionFormType::CMS_VERSION);
            $version = $this->castId($cmsVersionData['version']);

            $redirect = $this->submitVersionForm($request, $version, $idCmsPage);
        }

        if (!empty($redirect)) {
            return $this->redirectResponse($redirect);
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
        $cmsCurrentVersionDataTransfer = $cmsVersionDataHelper->mapToCmsVersionDataTransfer($cmsCurrentVersionTransfer);
        $cmsTargetVersionDataTransfer = new CmsVersionDataTransfer();

        if ($version !== null) {
            $cmsTargetVersionDataTransfer = $this->getCmsTargetVersionDataTransfer($cmsVersionDataHelper, $idCmsPage, $version);
        }

        return [
            'cmsCurrentPage' => $cmsCurrentVersionDataTransfer->getCmsPage(),
            'cmsCurrentGlossary' => $cmsCurrentVersionDataTransfer->getCmsGlossary(),
            'cmsTargetPage' => $cmsTargetVersionDataTransfer->getCmsPage(),
            'cmsTargetGlossary' => $cmsTargetVersionDataTransfer->getCmsGlossary(),
            'versionForm' => $versionForm->createView(),
            'cmsVersion' => $cmsCurrentVersionTransfer,
            'isPageTemplateWithPlaceholders' => $this->isPageTemplateWithPlaceholders($idCmsPage),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $version
     * @param int $idCmsPage
     *
     * @return string|null
     */
    protected function submitVersionForm(Request $request, $version, $idCmsPage)
    {
        if ($request->request->get('rollback') === null) {
            return null;
        }

        $redirectUrl = Url::generate(
            '/cms-gui/version-page/history',
            [static::URL_PARAM_ID_CMS_PAGE => $idCmsPage]
        )
            ->build();

        try {
            $cmsVersionTransfer = $this->getFactory()
                ->getCmsFacade()
                ->rollback($idCmsPage, $version);
        } catch (TemplateFileNotFoundException $exception) {
            $this->addErrorMessage('It is not possible to rollback to this version. The version you are trying to rollback to uses a template that does not exist anymore.');

            return $redirectUrl;
        }

        $this->addSuccessMessage('Rollback applied successfully. Page with version %s published.', ['%s' => $cmsVersionTransfer->getVersion()]);

        return $redirectUrl;
    }

    /**
     * @param \Spryker\Zed\CmsGui\Communication\Mapper\CmsVersionMapperInterface $cmsVersionDataHelper
     * @param int $idCmsPage
     * @param int $version
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    protected function getCmsTargetVersionDataTransfer(CmsVersionMapperInterface $cmsVersionDataHelper, $idCmsPage, $version)
    {
        $cmsTargetVersionTransfer = $this->getFactory()
            ->getCmsFacade()
            ->findCmsVersionByIdCmsPageAndVersion($idCmsPage, $version);

        if ($cmsTargetVersionTransfer === null) {
            throw new NotFoundHttpException(sprintf('CMS page with version `%s` not found.', $version));
        }

        return $cmsVersionDataHelper->mapToCmsVersionDataTransfer($cmsTargetVersionTransfer);
    }

    /**
     * @param int $idCmsPage
     *
     * @return bool
     */
    protected function isPageTemplateWithPlaceholders(int $idCmsPage): bool
    {
        $cmsGlossaryTransfer = $this->getFactory()->getCmsFacade()->findPageGlossaryAttributes($idCmsPage);

        if (!$cmsGlossaryTransfer) {
            return false;
        }

        return $cmsGlossaryTransfer->getGlossaryAttributes()->count() > 0;
    }
}
