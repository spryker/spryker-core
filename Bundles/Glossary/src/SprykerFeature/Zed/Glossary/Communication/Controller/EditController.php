<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Controller;

use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryCommunication;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Communication\GlossaryDependencyContainer;
use Generated\Shared\Transfer\KeyTranslationTransfer;

/**
 * @method GlossaryCommunication getFactory()
 * @method GlossaryDependencyContainer getDependencyContainer()
 * @method GlossaryFacade getFacade()
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

        $glossaryForm->handleRequest();

        if ($glossaryForm->isValid()) {
            $data = $glossaryForm->getData();

            $transfer = new KeyTranslationTransfer();
            $transfer->fromArray($data, true);

            $facade = $this->getFacade();
            $facade->saveGlossaryKeyTranslations($transfer);

            return $this->redirectResponse('/glossary/');
        }

        return $this->viewResponse([
            'form' => $glossaryForm->createView(),
        ]);
    }
}
