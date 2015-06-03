<?php

namespace SprykerFeature\Zed\Glossary\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Communication\GlossaryDependencyContainer;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method GlossaryDependencyContainer getDependencyContainer()
 * @method GlossaryFacade getFacade()
 * @method GlossaryQueryContainerInterface getQueryContainer()
 */
class FormController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function translationAction(Request $request)
    {
        $form = $this->getDependencyContainer()
            ->createKeyForm($request)
        ;
        $form->init();

        if ($form->isValid()) {

            // @todo check this code
            $formData = $form->getRequestData();

            $facade = $this->getDependencyContainer()->createGlossaryFacade();
            $facade->saveGlossaryKeyTranslations($formData);

            $this->getFacade()->saveTranslation($translation);
        }

        return $this->jsonResponse($form->renderData());
    }

}
