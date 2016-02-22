<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Glossary\Communication\GlossaryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Glossary\Business\GlossaryFacade getFacade()
 */
class EditController extends AbstractController
{

    const FORM_UPDATE_TYPE = 'update';
    const URL_PARAMETER_GLOSSARY_KEY = 'fk-glossary-key';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $formData = $this
            ->getFactory()
            ->createTranslationDataProvider()
            ->getData(
                $request->query->getInt(self::URL_PARAMETER_GLOSSARY_KEY),
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
