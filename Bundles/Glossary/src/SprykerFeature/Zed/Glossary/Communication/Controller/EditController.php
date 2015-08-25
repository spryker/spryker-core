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

    const FORM_UPDATE_TYPE = 'update';

    /**
     * @return array
     */
    public function indexAction()
    {
        $availableLocales = $this->getDependencyContainer()
            ->createEnabledLocales()
        ;

        $glossaryForm = $this->getDependencyContainer()
            ->createTranslationForm($availableLocales, self::FORM_UPDATE_TYPE)
        ;

        $glossaryForm->handleRequest();

        if ($glossaryForm->isValid()) {
            $data = $glossaryForm->getData();

            $keyTranslationTransfer = new KeyTranslationTransfer();
            $keyTranslationTransfer->fromArray($data, true);

            $facade = $this->getFacade();

            if ($facade->saveGlossaryKeyTranslations($keyTranslationTransfer)) {
                $this->addSuccessMessage('Saved entry to glossary.');
            } else {
                $this->addErrorMessage('Translations could not be saved');
            }

            return $this->redirectResponse('/glossary/');
        }

        return $this->viewResponse([
            'form' => $glossaryForm->createView(),
        ]);
    }
}
