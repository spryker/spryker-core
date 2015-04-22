<?php

namespace SprykerFeature\Zed\GlossaryExporter\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryTranslationTableMap;

class GlossaryExporterQueryContainer extends AbstractQueryContainer implements GlossaryExporterQueryContainerInterface
{
    /**
     * @param ModelCriteria $expandableQuery
     * @param string $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $locale)
    {
        $expandableQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyGlossaryTranslationTableMap::COL_ID_GLOSSARY_TRANSLATION,
            Criteria::INNER_JOIN
        );
        $expandableQuery->addJoin(
            SpyGlossaryTranslationTableMap::COL_FK_GLOSSARY_KEY,
            SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY,
            Criteria::INNER_JOIN
        );
        $expandableQuery->addJoin(
            SpyGlossaryTranslationTableMap::COL_FK_LOCALE,
            SpyLocaleTableMap::COL_ID_LOCALE,
            Criteria::INNER_JOIN
        );

        $expandableQuery->addAnd(SpyLocaleTableMap::COL_LOCALE_NAME, $locale, Criteria::EQUAL);
        $expandableQuery->addAnd(SpyLocaleTableMap::COL_IS_ACTIVE, true, Criteria::EQUAL);
        $expandableQuery->addAnd(SpyGlossaryKeyTableMap::COL_IS_ACTIVE, true, Criteria::EQUAL);
        $expandableQuery->addAnd(SpyGlossaryTranslationTableMap::COL_IS_ACTIVE, true, Criteria::EQUAL);

        $expandableQuery->clearSelectColumns();

        $expandableQuery->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE, 'translation_value');
        $expandableQuery->withColumn(SpyGlossaryKeyTableMap::COL_KEY, 'translation_key');

        return $expandableQuery;
    }
}
