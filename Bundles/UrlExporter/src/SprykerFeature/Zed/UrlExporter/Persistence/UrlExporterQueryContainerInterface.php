<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UrlExporter\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface UrlExporterQueryContainerInterface
{

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandRedirectQuery(ModelCriteria $expandableQuery);

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandUrlQuery(ModelCriteria $expandableQuery);

}
