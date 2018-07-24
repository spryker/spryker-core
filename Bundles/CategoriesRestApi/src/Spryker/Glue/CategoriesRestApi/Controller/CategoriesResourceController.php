<?php
/**
 * Created by PhpStorm.
 * User: poidenko
 * Date: 7/23/18
 * Time: 5:25 PM
 */

namespace Spryker\Glue\CategoriesRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CategoriesRestApi\CategoriesRestApiFactory getFactory()
 */
class CategoriesResourceController extends AbstractController
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        return $this->getFactory()
            ->createCategoriesReader()
            ->readCategoriesTree($restRequest->getMetadata()->getLocale());
    }
}
