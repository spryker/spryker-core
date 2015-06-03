<?php

namespace SprykerFeature\Zed\Glossary\Communication\Controller;

use Generated\Shared\Transfer\TranslationTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Communication\GlossaryDependencyContainer;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method GlossaryDependencyContainer getDependencyContainer()
 * @method GlossaryFacade getFacade()
 * @method GlossaryQueryContainerInterface getQueryContainer()
 */
class FormController extends AbstractController
{
    /**
     * @return JsonResponse
     */
    public function translationAction()
    {
        $form = $this->getDependencyContainer()
            ->createKeyForm()
        ;

        $form = $this->getDependencyContainer()->createTranslationForm();
        $form->init();

        if ($form->isValid()) {

            $translation = new TranslationTransfer();
            $translation->fromArray($form->getRequestData());

            $this->getFacade()->saveTranslation($translation);
        }

        return $this->jsonResponse($form->renderData());
    }

}
