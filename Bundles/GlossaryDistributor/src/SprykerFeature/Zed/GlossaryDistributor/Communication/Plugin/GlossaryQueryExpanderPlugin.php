<?php

namespace SprykerFeature\Zed\GlossaryDistributor\Communication\Plugin;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerFeature\Zed\Distributor\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\Distributor\Persistence\Propel\Map\SpyDistributorItemTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryKeyTableMap;
use SprykerFeature\Zed\Glossary\Persistence\Propel\Map\SpyGlossaryTranslationTableMap;

class GlossaryQueryExpanderPlugin extends AbstractPlugin implements
    QueryExpanderPluginInterface
{

    /**
     * @return string
     */
    public function getType()
    {
        return 'glossary_translation';
    }

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery)
    {
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

        $expandableQuery->clearSelectColumns();

        $expandableQuery->withColumn(SpyGlossaryTranslationTableMap::COL_VALUE);
        $expandableQuery->withColumn(SpyGlossaryTranslationTableMap::COL_IS_ACTIVE);
        $expandableQuery->withColumn(SpyGlossaryKeyTableMap::COL_KEY);
        $expandableQuery->withColumn(SpyGlossaryKeyTableMap::COL_IS_ACTIVE);
        $expandableQuery->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME);

        return $expandableQuery;
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }
}
