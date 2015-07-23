<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Controller;

use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryCommunication;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Glossary\Communication\GlossaryDependencyContainer;

/**
 * @method GlossaryCommunication getFactory()
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class EditController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $availableLocales = $this->getDependencyContainer()
            ->createEnabledLocales()
        ;

        $glossaryForm = $this->getDependencyContainer()
            ->createTranslationForm($availableLocales, 'update')
        ;
        $glossaryForm->init();

        $glossaryForm->handleRequest();

        if ($glossaryForm->isValid()) {
            $data = $glossaryForm->getData();

            $facade = $this->getDependencyContainer()->createGlossaryFacade();
            $facade->saveGlossaryKeyTranslations($data);
            // return $this->redirectResponse('/glossary/');
        }

        return $this->viewResponse([
            'form' => $glossaryForm->createView(),
        ]);
    }
}
