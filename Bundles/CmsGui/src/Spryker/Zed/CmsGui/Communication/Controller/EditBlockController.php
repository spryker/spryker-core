<?php


namespace Spryker\Zed\CmsGui\Communication\Controller;


use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException;
use Spryker\Zed\CmsGui\Communication\Form\Block\CmsBlockForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class EditBlockController extends AbstractController
{

    const URL_PARAM_ID_CMS_BLOCK = 'id-cms-block';
    const URL_PARAM_REDIRECT_URL = 'redirect-url';

    const REDIRECT_URL_DEFAULT = '/cms-gui/list-block/';

    public function indexAction(Request $request)
    {
        $idCmsBlock = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_BLOCK));

        $cmsBlockFormTypeDataProvider = $this->getFactory()
            ->createCmsBlockFormDataProvider();

        $cmsBlockForm = $this->getFactory()
            ->createCmsBlockForm($cmsBlockFormTypeDataProvider, $idCmsBlock)
            ->handleRequest($request);

        if ($cmsBlockForm->isSubmitted()) {
            $isUpdated = $this->updateCmsPage($cmsBlockForm);

            if ($isUpdated) {
                $redirectUrl = $this->createEditCmsBlockUrl($idCmsBlock);
                return $this->redirectResponse($redirectUrl);
            }
        }

        $availableLocales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        $cmsBlockTransfer = $this->getFactory()
            ->getCmsBlockFacade()
            ->findCmsBlockId($idCmsBlock);

        return [
            'idCmsBlock' => $idCmsBlock,
            'cmsBlockForm' => $cmsBlockForm->createView(),
            'availableLocales' => $availableLocales,
            'cmsBlock' => $cmsBlockTransfer,
        ];

    }

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

    /**
     * @param \Symfony\Component\Form\FormInterface $cmsBlockForm
     *
     * @return bool
     */
    protected function updateCmsPage(FormInterface $cmsBlockForm)
    {
        if ($cmsBlockForm->isValid()) {
            try {
                $this->getFactory()
                    ->getCmsBlockFacade()
                    ->updateCmsBlock($cmsBlockForm->getData());

                $this->addSuccessMessage('Block is successfully updated.');

                return true;
            } catch (CmsBlockTemplateNotFoundException $exception) {
                $cmsBlockForm
                    ->get(CmsBlockForm::FIELD_FK_TEMPLATE)
                    ->addError(new FormError('Selected template doesn\'t exist anymore'));
            }
        } else {
            $this->addErrorMessage('Invalid data provided');
        }

        return false;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return string
     */
    protected function createEditCmsBlockUrl($idCmsBlock)
    {
        return Url::generate(
            '/cms-gui/edit-block/index',
            [static::URL_PARAM_ID_CMS_BLOCK => $idCmsBlock]
        )->build();
    }

}