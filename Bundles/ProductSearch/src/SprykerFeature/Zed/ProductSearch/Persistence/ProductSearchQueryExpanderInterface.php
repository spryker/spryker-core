<?php

namespace SprykerFeature\Zed\ProductSearch\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Shared\Dto\LocaleDto;

interface ProductSearchQueryExpanderInterface
{
    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleDto $locale
     *
     * @return ModelCriteria
     */
    public function expandProductQuery(ModelCriteria $expandableQuery, LocaleDto $locale);
}
