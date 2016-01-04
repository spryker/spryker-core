<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Spryker\Zed\Glossary\Communication\GlossaryCommunicationFactory;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method GlossaryCommunicationFactory getFactory()
 * @method GlossaryFacade getFacade()
 */
class EditController extends AbstractController
{

    const FORM_UPDATE_TYPE = 'update';

    /**
     * @return array
     */
    public function indexAction(Request $request)
    {
        $availableLocales = $this->getFactory()
            ->createEnabledLocales();

        $glossaryForm = $this->getFactory()
            ->createTranslationForm($availableLocales, self::FORM_UPDATE_TYPE);

        $glossaryForm->handleRequest($request);

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
