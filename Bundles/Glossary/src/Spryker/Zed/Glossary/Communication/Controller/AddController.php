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
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryRepositoryInterface getRepository()
 */
class AddController extends AbstractController
{
    /**
     * @var string
     */
    public const FORM_ADD_TYPE = 'add';

    /**
     * @var string
     */
    public const MESSAGE_CREATE_SUCCESS = 'Translation %d was created successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $glossaryForm = $this
            ->getFactory()
            ->getTranslationAddForm();

        $glossaryForm->handleRequest($request);

        if ($glossaryForm->isSubmitted() && $glossaryForm->isValid()) {
            $data = $glossaryForm->getData();

            $keyTranslationTransfer = new KeyTranslationTransfer();
            $keyTranslationTransfer->fromArray($data, true);

            $glossaryFacade = $this->getFacade();
            $glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);
            /** @var string|null $keyName */
            $keyName = $keyTranslationTransfer->getGlossaryKey();
            $idGlossaryKey = $this->getFacade()->getKeyIdentifier($keyName);

            $this->addSuccessMessage(static::MESSAGE_CREATE_SUCCESS, ['%d' => $idGlossaryKey]);

            return $this->redirectResponse('/glossary');
        }

        return $this->viewResponse([
            'form' => $glossaryForm->createView(),
        ]);
    }
}
