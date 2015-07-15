<?php

namespace SprykerFeature\Zed\GlossaryDistributor\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Distributor\Persistence\Propel\Map\SpyDistributorItemTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryTranslationTableMap;

class GlossaryDistributorQueryContainer extends AbstractQueryContainer
{

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandTranslationQueryToDistribute(ModelCriteria $expandableQuery)
    {
        $expandableQuery->clearSelectColumns();
        $expandableQuery->addJoin(
            SpyDistributorItemTableMap::COL_FK_GLOSSARY_TRANSLATION,
            SpyGlossaryTranslationTableMap::COL_ID_GLOSSARY_TRANSLATION,
            Criteria::INNER_JOIN
        );
        $expandableQuery->addJoin(
            SpyGlossaryTranslationTableMap::COL_FK_LOCALE,
            SpyLocaleTableMap::COL_ID_LOCALE,
            Criteria::INNER_JOIN
        );
        $expandableQuery->addJoin(
            SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY,
            SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY,
            Criteria::INNER_JOIN
        );

        $expandableQuery->withColumn(SpyGlossaryKeyTableMap::COL_KEY, 'translation_key');
        $expandableQuery->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, 'translation_value');
        $expandableQuery->withColumn(SpyGlossaryTranslationTableMap::COL_IS_ACTIVE, 'translation_is_active');
        $expandableQuery->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, 'translation_locale');

        return $expandableQuery;
    }

}
