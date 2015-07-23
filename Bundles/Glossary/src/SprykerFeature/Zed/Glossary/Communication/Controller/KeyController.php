<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Controller;

use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryCommunication;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Glossary\Communication\GlossaryDependencyContainer;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKey;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method GlossaryCommunication getFactory()
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class KeyController extends AbstractController
{
    const ID_GLOSSARY_KEY = 'id_glossary_key';

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

            $key = $this->createKey();
            $key->setNew(true);

            $key->setKey($data[$this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_KEY)]);
            $key->setIsActive(true === $data[$this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_IS_ACTIVE)] ? 1 : 0);

            $key->save();

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

            $key = $this->createKey();
            $key->setNew(false);

            $key->setIdGlossaryKey($data[$this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY)]);
            $key->setKey($data[$this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_KEY)]);
            $key->setIsActive(true === $data[$this->cutTablePrefix(SpyGlossaryKeyTableMap::COL_IS_ACTIVE)] ? 1 : 0);

            $key->save();

            return $this->redirectResponse('/glossary/key/');
        }

        return $this->viewResponse([
            'form' => $keyForm->createView(),
        ]);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function cutTablePrefix($key) {
        $position = mb_strpos($key, '.');
        return (false !== $position) ? mb_substr($key, $position + 1) : $key;
    }

    /**
     * @return SpyGlossaryKey
     */
    public function createKey()
    {
        return new SpyGlossaryKey();
    }

}
