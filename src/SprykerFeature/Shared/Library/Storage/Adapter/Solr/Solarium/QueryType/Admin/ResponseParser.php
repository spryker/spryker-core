<?php
namespace SprykerFeature\Shared\Library\Storage\Adapter\Solr\Solarium\QueryType\Admin;

use Solarium\Core\Query\ResponseParser as ResponseParserAbstract;
use Solarium\Core\Query\ResponseParserInterface as ResponseParserInterface;

/**
 * Class ResponseParser
 * @package SprykerFeature\Shared\Library\Storage\Adapter\Solr\Solarium\QueryType\Admin
 */
class ResponseParser extends ResponseParserAbstract implements ResponseParserInterface
{
    /**
     * Get result data for the response
     *
     * @throws \RuntimeException
     * @param \Solarium\Core\Client\Request $result
     * @return array
     */
    public function parse($result)
    {
        //no need to prepare the result till, if so use getData and getQuery to prepare the
        //result before returning
        return $result;
    }
}
