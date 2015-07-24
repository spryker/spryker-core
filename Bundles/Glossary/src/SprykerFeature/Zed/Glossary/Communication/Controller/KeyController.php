<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Controller;

use Generated\Shared\Transfer\TranslationTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryCommunication;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Communication\GlossaryDependencyContainer;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKey;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryTranslation;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method GlossaryCommunication getFactory()
 * @method GlossaryDependencyContainer getDependencyContainer()
 * @method GlossaryFacade getFacade()
 */
class KeyController extends AbstractController
{

    const ID_GLOSSARY_KEY = 'id_glossary_key';

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function ajaxAction(Request $request)
    {
        $idGlossaryKey = $request->get(self::ID_GLOSSARY_KEY);

        $result = $this->getFacade()
            ->getTranslations($idGlossaryKey)
        ;

        return $this->jsonResponse($result);
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getDependencyContainer()
            ->createKeyTable()
        ;
        $table->init();

        return $this->viewResponse([
            'keyTable' => $table,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()
            ->createKeyTable()
        ;
        $table->init();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     * @return array|RedirectResponse
     */
    public function addAction()
    {

        $keyForm = $this->getDependencyContainer()
            ->createKeyForm('add', false)
        ;
        $keyForm->init();

        $keyForm->handleRequest();

        if ($keyForm->isValid()) {
            $data = $keyForm->getData();

            $result = $this->getFacade()
                ->createKey($data)
            ;

            return $this->redirectResponse('/glossary/key/');
        }

        return $this->viewResponse([
            'form' => $keyForm->createView(),
        ]);
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     * @return array|RedirectResponse
     */
    public function editAction(Request $request)
    {
        $idGlossaryKey = $request->get(self::ID_GLOSSARY_KEY);

        $keyForm = $this->getDependencyContainer()
            ->createKeyForm('update', $idGlossaryKey)
        ;
        $keyForm->init();

        $keyForm->handleRequest();

        if ($keyForm->isValid()) {
            $data = $keyForm->getData();

            $result = $this->getFacade()
                ->updateKey($data)
            ;

            return $this->redirectResponse('/glossary/key/');
        }

        return $this->viewResponse([
            'form' => $keyForm->createView(),
        ]);
    }

    /**
     * @return SpyGlossaryKey
     */
    public function createKey()
    {
        return new SpyGlossaryKey();
    }

    /**
     * @return SpyGlossaryTranslation
     */
    public function createTranslation()
    {
        return new TranslationTransfer();
    }

}
