<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Communication\Controller;

use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Glossary\Communication\GlossaryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Glossary\Business\GlossaryFacadeInterface getFacade()
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface getQueryContainer()
 */
class KeyController extends AbstractController
{
    public const TERM = 'term';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ajaxAction(Request $request)
    {
        $term = $request->get(self::TERM);

        $key = $this->getQueryContainer()
            ->queryKey($term)
            ->findOne();

        $idGlossaryKey = false;
        if (!empty($key)) {
            $idGlossaryKey = $key->getIdGlossaryKey();
        }

        $translations = [];
        if ($idGlossaryKey) {
            $translations = $this->getQueryContainer()
                ->queryTranslations()
                ->findByFkGlossaryKey($idGlossaryKey);
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function suggestAction(Request $request)
    {
        $term = $request->get(self::TERM);

        $keys = $this->getQueryContainer()
            ->queryByKey($term)->find();

        $result = [];
        if ($keys->count()) {
            $keys = $keys->toArray(null, false, TableMap::TYPE_COLNAME);

            foreach ($keys as $value) {
                $result[] = $value[SpyGlossaryKeyTableMap::COL_KEY];
            }
        }

        return $this->jsonResponse($result);
    }
}
