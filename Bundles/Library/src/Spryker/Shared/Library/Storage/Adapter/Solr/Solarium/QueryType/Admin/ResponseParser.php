<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Storage\Adapter\Solr\Solarium\QueryType\Admin;

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
