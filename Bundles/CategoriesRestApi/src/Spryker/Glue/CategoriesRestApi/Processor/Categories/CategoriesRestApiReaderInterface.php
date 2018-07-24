<?php
/**
 * Created by PhpStorm.
 * User: poidenko
 * Date: 7/23/18
 * Time: 5:33 PM
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Categories;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CategoriesRestApiReaderInterface
{
    /**
     * @param string $locale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readCategoriesTree(string $locale): RestResponseInterface;
}
