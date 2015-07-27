<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Controller;

use Generated\Zed\Ide\FactoryAutoCompletion\GlossaryCommunication;
use Propel\Runtime\Map\TableMap;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Communication\GlossaryDependencyContainer;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainer;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryTranslationTableMap;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method GlossaryCommunication getFactory()
 * @method GlossaryDependencyContainer getDependencyContainer()
 * @method GlossaryFacade getFacade()
 * @method GlossaryQueryContainer getQueryContainer()
 */
class KeyController extends AbstractController
{

    const TERM = 'term';

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function ajaxAction(Request $request)
    {
        $term = $request->get(self::TERM);

        $key = $this->getQueryContainer()
            ->queryKey($term)
            ->findOne()
        ;

        $idGlossaryKey = false;
        if (!empty($key)) {
            $idGlossaryKey = $key->getIdGlossaryKey();
        }

        $translations = [];
        if ($idGlossaryKey) {
            $translations = $this->getQueryContainer()
                ->queryTranslations()
                ->findByFkGlossaryKey($idGlossaryKey)
            ;

        }

        $result = [];
        if (!empty($translations)) {
            $translations = $translations->toArray(null, false, TableMap::TYPE_COLNAME);

            foreach ($translations as $value) {
                $result[$value[SpyGlossaryTranslationTableMap::COL_FK_LOCALE]] = $value[SpyGlossaryTranslationTableMap::COL_VALUE];
            }
        }

        return $this->jsonResponse($result);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function suggestAction(Request $request)
    {
        $term = $request->get(self::TERM);

        $keys = $this->getQueryContainer()
            ->queryByKey($term)->find()
        ;

        if ($keys) {
            $keys = $keys->toArray(null, false, TableMap::TYPE_COLNAME);

            foreach ($keys as $value) {
                $result[] = $value[SpyGlossaryKeyTableMap::COL_KEY];
            }
        }

        return $this->jsonResponse($result);
    }

}
