<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Controller;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface getQueryContainer()
 */
class UrlSuggestionController extends AbstractController
{
    public const PARAM_TERM = 'term';
    public const PARAM_ID_LOCALE = 'id-locale';
    public const SUGGESTION_LIMIT = 10;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function cmsPageAction(Request $request)
    {
        $searchTerm = $request->query->get(self::PARAM_TERM);
        $idLocale = $this->castId($request->query->getInt(self::PARAM_ID_LOCALE));

        $query = $this->createCmsPageUrlSuggestionQuery($searchTerm, $idLocale);

        return $this->getSearchSuggestions($query);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function categoryAction(Request $request)
    {
        $searchTerm = $request->query->get(self::PARAM_TERM);
        $idLocale = $this->castId($request->query->getInt(self::PARAM_ID_LOCALE));

        $query = $this->createCategoryUrlSuggestionQuery($searchTerm, $idLocale);

        return $this->getSearchSuggestions($query);
    }

    /**
     * @param string $searchTerm
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function createCmsPageUrlSuggestionQuery($searchTerm, $idLocale)
    {
        return $this->getQueryContainer()->queryCmsPageUrlSuggestions($searchTerm, $idLocale);
    }

    /**
     * @param string $searchTerm
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function createCategoryUrlSuggestionQuery($searchTerm, $idLocale)
    {
        return $this->getQueryContainer()->queryCategoryNodeUrlSuggestions($searchTerm, $idLocale);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getSearchSuggestions(ModelCriteria $query)
    {
        $results = [];

        $records = $query->limit(static::SUGGESTION_LIMIT)->find();
        foreach ($records as $record) {
            $results[] = [
                'label' => $record['name'] . ' (' . $record['url'] . ')',
                'value' => $record['url'],
            ];
        }

        return $this->jsonResponse($results);
    }
}
