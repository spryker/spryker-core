<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\Solr\Solarium\QueryType\Admin;

use Solarium\Core\Query\ResponseParser as ResponseParserAbstract;
use Solarium\Core\Query\ResponseParserInterface as ResponseParserInterface;

/**
 * Class ResponseParser
 */
class ResponseParser extends ResponseParserAbstract implements ResponseParserInterface
{

    /**
     * Get result data for the response
     *
     *
     * @param \Solarium\Core\Client\Request $result
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    public function parse($result)
    {
        //no need to prepare the result till, if so use getData and getQuery to prepare the
        //result before returning
        return $result;
    }

}
