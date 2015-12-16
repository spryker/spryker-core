<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Glossary\Business\GlossaryFacade;
use Spryker\Zed\Glossary\Communication\GlossaryDependencyContainer;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method GlossaryFacade getFacade()
 * @method GlossaryQueryContainerInterface getQueryContainer()
 * @method GlossaryDependencyContainer getCommunicationFactory()
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
        $keys = $this->getCommunicationFactory()
            ->createQueryContainer()
            ->queryActiveKeysByName('%' . $term . '%')
            ->select([
                SpyGlossaryKeyTableMap::COL_KEY,
            ])
            ->find()
            ->toArray();

        return new JsonResponse($keys);
    }

}
