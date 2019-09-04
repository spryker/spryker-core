<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Query;

use Elastica\Query\Nested;
use Generated\Shared\Transfer\FacetConfigTransfer;

class NestedTermQuery extends AbstractNestedQuery
{
    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected $facetConfigTransfer;

    /**
     * @var string
     */
    protected $filterValue;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param string $filterValue
     * @param \Spryker\Client\SearchElasticsearch\Query\QueryBuilderInterface $queryBuilder
     */
    public function __construct(FacetConfigTransfer $facetConfigTransfer, string $filterValue, QueryBuilderInterface $queryBuilder)
    {
        $this->facetConfigTransfer = $facetConfigTransfer;
        $this->filterValue = $filterValue;

        parent::__construct($queryBuilder);
    }

    /**
     * @return \Elastica\Query\Nested
     */
    public function createNestedQuery(): Nested
    {
        $fieldName = $this->facetConfigTransfer->getFieldName();
        $nestedFieldName = $this->facetConfigTransfer->getName();

        return $this->bindMultipleNestedQuery($fieldName, [
            $this->queryBuilder->createTermQuery($fieldName . self::FACET_NAME_SUFFIX, $nestedFieldName),
            $this->queryBuilder->createTermQuery($fieldName . self::FACET_VALUE_SUFFIX, $this->filterValue),
        ]);
    }
}
