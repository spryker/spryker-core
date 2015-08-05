<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;

use Generated\Shared\Transfer\PageTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\CmsDependencyProvider;
use SprykerFeature\Zed\Cms\Communication\Form\CmsPageForm;
use SprykerFeature\Zed\Cms\Communication\Table\CmsPageTable;
use SprykerFeature\Zed\Url\Business\UrlFacade;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CmsDependencyContainer getDependencyContainer()
 * @method CmsFacade getFacade()
 */
class PageController extends AbstractController
{

    const REDIRECT_ADDRESS = '/cms/glossary/';

    /**
     *
     * @return array
     */
    public function indexAction()
    {

        $table = $this->getDependencyContainer()
            ->createCmsPageTable()
        ;

        return [
            'pages' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()
            ->createCmsPageTable()
        ;

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @return array
     */
    public function addAction()
    {
        $form = $this->getDependencyContainer()
            ->createCmsPageForm('add')
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            $pageTransfer = new PageTransfer();
            $pageTransfer->fromArray($data, true);

            $pageTransfer = $this->getFacade()
                ->savePage($pageTransfer)
            ;

            $urlTransfer = $this->getFacade()
                ->createPageUrl($pageTransfer, $data[CmsPageForm::URL])
            ;

            $this->getUrlFacade()
                ->touchUrlActive($urlTransfer->getIdUrl())
            ;

            return $this->redirectResponse(self::REDIRECT_ADDRESS . '?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $pageTransfer->getIdCmsPage());
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function editAction(Request $request)
    {

        $idPage = $request->get(CmsPageTable::REQUEST_ID_PAGE);

        $form = $this->getDependencyContainer()
            ->createCmsPageForm('update', $idPage)
        ;

        $form->handleRequest();

        if ($form->isValid()) {
            $data = $form->getData();

            $pageTransfer = new PageTransfer();
            $pageTransfer->fromArray($data, true);

            $pageTransfer = $this->getFacade()
                ->savePage($pageTransfer)
            ;

            $spyUrl = $this->getQueryContainer()
                ->queryUrlById($data['id_url'])
                ->findOne()
            ;

            $urlTransfer = (new UrlTransfer())->fromArray($spyUrl->toArray(), true);

            $urlTransfer->setFkPage($pageTransfer->getIdCmsPage());
            $urlTransfer->setResourceId($spyUrl->getResourceId());
            $urlTransfer->setResourceType($spyUrl->getResourceType());

            if (intval($data['cur_temp']) !== intval($data['fkTemplate'])) {
                $this->deleteMappedGlossary($idPage);
            }

            $urlTransfer->setUrl($data['url']);

            $urlTransfer = $this->getUrlFacade()
                ->saveUrl($urlTransfer)
            ;

            $this->getUrlFacade()
                ->touchUrlActive($urlTransfer->getIdUrl())
            ;

            return $this->redirectResponse(self::REDIRECT_ADDRESS . '?' . CmsPageTable::REQUEST_ID_PAGE . '=' . $pageTransfer->getIdCmsPage());
        }

        return $this->viewResponse([
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return UrlFacade
     */
    private function getUrlFacade()
    {
        return $this->getDependencyContainer()
            ->getProvidedDependency(CmsDependencyProvider::URL_BUNDLE)
            ;
    }

    private function deleteMappedGlossary($idPage)
    {
        $mappedGlossaries = $this->getQueryContainer()
            ->queryGlossaryKeyMappingsByPageId($idPage)
            ->find()
        ;
        $pageTransfer = (new PageTransfer())->setIdCmsPage($idPage);
        foreach ($mappedGlossaries->getData() as $glossaryMapping) {
            $this->getFacade()
                ->deletePageKeyMapping($pageTransfer, $glossaryMapping->getPlaceholder())
            ;
        }
    }
}
