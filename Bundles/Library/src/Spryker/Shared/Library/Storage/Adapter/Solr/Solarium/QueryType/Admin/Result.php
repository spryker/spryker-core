<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Storage\Adapter\Solr\Solarium\QueryType\Admin;

use Solarium\Core\Query\Result\QueryType as BaseResult;

/**
 * Class Result
 */
class Result extends BaseResult
{

    /**
     * Ensures the response is parsed and returns a property.
     *
     * @param string $property The name of the class member variable.
     *
     * @return mixed The value of the property.
     */
    public function returnProperty($property)
    {
        $this->parseResponse();

        return $this->$property;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        // TODO: remove this debug output
        echo PHP_EOL . '<hr /><pre>';
        var_dump($this->getData());
        echo __CLASS__ . ' ' . __FILE__ . ':' . __LINE__ . '';
        echo '</pre><hr />' . PHP_EOL;
        exit();
        //return $this;
    }

}
