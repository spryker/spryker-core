<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Cms\Business\Exception\CannotActivatePageException;
use Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException;
use Spryker\Zed\CmsGui\CmsGuiConfig;
use Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageFormType;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class EditPageController extends AbstractController
{

    const URL_PARAM_ID_CMS_PAGE = 'id-cms-page';
    const URL_PARAM_REDIRECT_URL = 'redirect-url';
    const INVALID_DATA_PROVIDED_ERROR_MESSAGE = 'Invalid data provided.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $this->getFactory()
            ->getCmsFacade()
            ->syncTemplate(CmsGuiConfig::CMS_FOLDER_PATH);

        $idCmsPage = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_PAGE));

        $cmsPageFormTypeDataProvider = $this->getFactory()
            ->createCmsPageFormTypeDataProvider();

        $pageForm = $this->getFactory()
            ->createCmsPageForm($cmsPageFormTypeDataProvider, $idCmsPage)
            ->handleRequest($request);

        if ($pageForm->isSubmitted()) {
            $redirectUrl = $this->updateCmsPage($pageForm, $idCmsPage);

            if (!empty($redirectUrl)) {
                return $this->redirectResponse($redirectUrl);
            }
        }

        $availableLocales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        $cmsVersion = $this->getFactory()
            ->getCmsFacade()
            ->findLatestCmsVersionByIdCmsPage($idCmsPage);

        $cmsPageTransfer = $this->getFactory()
            ->getCmsFacade()
            ->findCmsPageById($idCmsPage);

        $pageTabs = $this->getFactory()->createPageTabs();
        return [
            'pageTabs' => $pageTabs->createView(),
            'pageForm' => $pageForm->createView(),
            'availableLocales' => $availableLocales,
            'idCmsPage' => $idCmsPage,
            'cmsVersion' => $cmsVersion,
            'cmsPage' => $cmsPageTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $pageForm
     * @param int $idCmsPage
     *
     * @return string|null
     */
    protected function updateCmsPage($pageForm, $idCmsPage)
    {
        if ($pageForm->isValid()) {
            try {
                $this->getFactory()
                    ->getCmsFacade()
                    ->updatePage($pageForm->getData());

                $this->addSuccessMessage('Page successfully updated.');

                return $this->createEditPageUrl($idCmsPage);
            } catch (TemplateFileNotFoundException $exception) {
                $this->addErrorMessage(static::INVALID_DATA_PROVIDED_ERROR_MESSAGE);
                $error = $this->createTemplateErrorForm();
                $pageForm->get(CmsPageFormType::FIELD_FK_TEMPLATE)->addError($error);
            }
        } else {
            $this->addErrorMessage(static::INVALID_DATA_PROVIDED_ERROR_MESSAGE);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request)
    {
        $idCmsPage = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_PAGE));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        try {
            $this->getFactory()
                ->getCmsFacade()
                ->activatePage($idCmsPage);

            $this->addSuccessMessage('Page successfully activated.');

        } catch (CannotActivatePageException $exception) {
             $this->addErrorMessage($exception->getMessage());
        } finally {
            return $this->redirectResponse($redirectUrl);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request)
    {
        $idCmsPage = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_PAGE));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL);

        $this->getFactory()
            ->getCmsFacade()
            ->deactivatePage($idCmsPage);

        $this->addSuccessMessage('Page successfully deactivated.');

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param int $idCmsPage
     *
     * @return string
     */
    protected function createEditPageUrl($idCmsPage)
    {
        return Url::generate(
            '/cms-gui/edit-page/index',
            [static::URL_PARAM_ID_CMS_PAGE => $idCmsPage]
        )->build();
    }

    /**
     * @return \Symfony\Component\Form\FormError
     */
    protected function createTemplateErrorForm()
    {
        return new FormError("Selected template doesn't exist anymore");
    }

}
