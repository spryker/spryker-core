<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Storage\Adapter\Solr\Solarium\QueryType\Admin;

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
     * @param \Solarium\Core\Query\QueryInterface $query
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
