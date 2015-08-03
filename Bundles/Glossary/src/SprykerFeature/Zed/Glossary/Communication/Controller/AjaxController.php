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

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function keysAction(Request $request)
    {
        $term = $request->query->get(self::SEARCH_TERM);
        $keys = $this->getDependencyContainer()
            ->createQueryContainer()
            ->queryActiveKeysByName('%' . $term . '%')
            ->select([
                SpyGlossaryKeyTableMap::COL_KEY
            ])
            ->find()
            ->toArray()
        ;

        return new JsonResponse($keys);
    }
}
