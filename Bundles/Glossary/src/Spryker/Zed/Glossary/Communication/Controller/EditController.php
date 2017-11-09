<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Communication\Controller;

use Generated\Shared\Transfer\KeyTranslationTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Glossary\Communication\GlossaryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface getFacade()
 */
class EditController extends AbstractController
{
    const FORM_UPDATE_TYPE = 'update';
    const URL_PARAMETER_GLOSSARY_KEY = 'fk-glossary-key';

    const MESSAGE_UPDATE_SUCCESS = 'Translation %d was updated successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idGlossaryKey = $this->castId($request->query->get(static::URL_PARAMETER_GLOSSARY_KEY));
        $formData = $this
            ->getFactory()
            ->createTranslationDataProvider()
            ->getData(
                $idGlossaryKey,
                $this->getFactory()->getEnabledLocales()
            );

        $glossaryForm = $this
            ->getFactory()
            ->createTranslationUpdateForm($formData);

        $glossaryForm->handleRequest($request);

        if ($glossaryForm->isValid()) {
            $data = $glossaryForm->getData();

            $keyTranslationTransfer = new KeyTranslationTransfer();
            $keyTranslationTransfer->fromArray($data, true);

            $glossaryFacade = $this->getFacade();

            if ($glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer)) {
                $this->addSuccessMessage(sprintf(static::MESSAGE_UPDATE_SUCCESS, $idGlossaryKey));
            } else {
                $this->addErrorMessage('Translations could not be saved');
            }

            return $this->redirectResponse('/glossary');
        }

        return $this->viewResponse([
            'form' => $glossaryForm->createView(),
            'idGlossaryKey' => $idGlossaryKey,
        ]);
    }
}
