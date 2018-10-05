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
    public const FORM_UPDATE_TYPE = 'update';
    public const URL_PARAMETER_GLOSSARY_KEY = 'fk-glossary-key';
    public const MESSAGE_UPDATE_SUCCESS = 'Translation %d was updated successfully.';
    public const MESSAGE_UPDATE_ERROR = 'Glossary entry was not updated.';

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
            ->getTranslationUpdateForm($formData);

        $glossaryForm->handleRequest($request);

        if ($glossaryForm->isSubmitted() && $glossaryForm->isValid()) {
            $data = $glossaryForm->getData();

            $keyTranslationTransfer = new KeyTranslationTransfer();
            $keyTranslationTransfer->fromArray($data, true);

            $glossaryFacade = $this->getFacade();

            if ($glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer)) {
                $this->addSuccessMessage(sprintf(static::MESSAGE_UPDATE_SUCCESS, $idGlossaryKey));
                return $this->redirectResponse('/glossary');
            }

            $this->addErrorMessage(static::MESSAGE_UPDATE_ERROR);
            return $this->redirectResponse('/glossary');
        }

        return $this->viewResponse([
            'form' => $glossaryForm->createView(),
            'idGlossaryKey' => $idGlossaryKey,
        ]);
    }
}
