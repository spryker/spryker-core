<?php

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Controller;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiQueryContainerInterface getQueryContainer()
 */
class UrlSuggestionController extends AbstractController
{
    const PARAM_TERM = 'term';
    const PARAM_ID_LOCALE = 'id-locale';
    const SUGGESTION_LIMIT = 10;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function filterAction(Request $request)
    {
        $searchTerm = $request->query->get(self::PARAM_TERM);
        $idLocale = $this->castId($request->query->getInt(self::PARAM_ID_LOCALE));

        $query = $this->createFilterSuggestionQuery($searchTerm, $idLocale);

        return $this->getSearchSuggestions($query);
    }

    /**
     * @param string $searchTerm
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function createFilterSuggestionQuery($searchTerm, $idLocale)
    {
        return $this->getQueryContainer()->queryFilterSuggestions($searchTerm, $idLocale);
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
