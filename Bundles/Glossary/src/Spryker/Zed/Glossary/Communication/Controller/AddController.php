<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Spryker\Zed\Glossary\Communication\GlossaryCommunicationFactory;
use Generated\Shared\Transfer\KeyTranslationTransfer;

/**
 * @method GlossaryCommunicationFactory getFactory()
 * @method GlossaryFacade getFacade()
 */
class AddController extends AbstractController
{

    const FORM_ADD_TYPE = 'add';

    /**
     * @return array
     */
    public function indexAction()
    {
        $availableLocales = $this->getFactory()
            ->createEnabledLocales();

        $glossaryForm = $this->getFactory()
            ->createTranslationForm($availableLocales, self::FORM_ADD_TYPE);

        $glossaryForm->handleRequest();

        if ($glossaryForm->isValid()) {
            $data = $glossaryForm->getData();

            $keyTranslationTransfer = new KeyTranslationTransfer();
            $keyTranslationTransfer->fromArray($data, true);

            $facade = $this->getFacade();
            $facade->saveGlossaryKeyTranslations($keyTranslationTransfer);

            $this->addSuccessMessage('Saved entry to glossary.');

            return $this->redirectResponse('/glossary/');
        }

        return $this->viewResponse([
            'form' => $glossaryForm->createView(),
        ]);
    }

}
