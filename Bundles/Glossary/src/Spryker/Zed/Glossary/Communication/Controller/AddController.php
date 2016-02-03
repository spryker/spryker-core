<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Glossary\Communication\GlossaryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Glossary\Business\GlossaryFacade getFacade()
 */
class AddController extends AbstractController
{

    const FORM_ADD_TYPE = 'add';

    /**
     * @return array
     */
    public function indexAction(Request $request)
    {
        $availableLocales = $this->getFactory()
            ->getEnabledLocales();

        $glossaryForm = $this->getFactory()
            ->createTranslationForm($availableLocales, self::FORM_ADD_TYPE);

        $glossaryForm->handleRequest($request);

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
