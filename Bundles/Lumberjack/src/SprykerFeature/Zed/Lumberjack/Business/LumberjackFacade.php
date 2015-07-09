<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Lumberjack\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\Lumberjack\Business\Model\ElasticSearch\Proxy;

class LumberjackFacade extends AbstractFacade
{

    /**
     * @param array $getParams
     * @param array $postData
     * @param string $type
     *
     * @return string
     */
    public function getSearch(array $getParams, array $postData, $type = Proxy::SEARCH_TYPE_SINGLE)
    {
        return $this->factory->createModelElasticSearchProxy()->getSearch($getParams, $postData, $type);
    }

    /**
     * @param string $json
     * @param string $fieldDelimiter
     * @param string $stringDelimiter
     *
     * @return string
     */
    public function getCsvFromElasticSearchJsonResponse($json, $fieldDelimiter = ';', $stringDelimiter = '"')
    {
        return $this->factory->createModelElasticSearchExportCsv()
            ->getCsvFromElasticSearchJsonResponse($json, $fieldDelimiter, $stringDelimiter);
    }

}
