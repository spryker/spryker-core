<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\Solr\Solarium\QueryType\Admin;

use Solarium\Core\Query\RequestBuilder as BaseRequestBuilder;
use Solarium\Core\Query\QueryInterface;

/**
 * Class RequestBuilder
 */
class RequestBuilder extends BaseRequestBuilder
{

    /**
     * Build request for a system query
     *
     * @param QueryInterface $query
     *
     * @return \Solarium\Core\Client\Request
     */
    public function build(QueryInterface $query)
    {
        $request = parent::build($query);
//        $request->addParam('wt', 'json');
//        $request->addParam('omitHeader', 'true');
        return $request;
    }

}
