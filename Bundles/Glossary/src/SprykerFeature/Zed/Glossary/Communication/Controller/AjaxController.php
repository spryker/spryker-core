<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Glossary\Communication\GlossaryDependencyContainer;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method GlossaryFacade getFacade()
 * @method GlossaryQueryContainerInterface getQueryContainer()
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class AjaxController extends AbstractController
{
    const SEARCH_TERM = 'term';
    const AUTOCOMPLETE_LABEL = 'label';
    const AUTOCOMPLETE_VALUE = 'value';

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function keysAction(Request $request)
    {
        $term = $request->query->get(self::SEARCH_TERM);
        $keys = $this
            ->getDependencyContainer()
            ->createQueryContainer()
            ->queryActiveKeysByName('%' . $term . '%')
            ->withColumn(
                SpyGlossaryKeyTableMap::COL_KEY,
                self::AUTOCOMPLETE_LABEL
            )
            ->withColumn(
                SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY,
                self::AUTOCOMPLETE_VALUE
            )
            ->select([
                self::AUTOCOMPLETE_LABEL,
                self::AUTOCOMPLETE_VALUE
            ])
            ->find()
            ->toArray()
        ;

        return new JsonResponse($keys);
    }
}
