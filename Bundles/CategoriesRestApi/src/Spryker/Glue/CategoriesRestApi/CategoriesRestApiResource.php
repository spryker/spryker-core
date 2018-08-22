<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractRestResource;

/**
 * @method \Spryker\Glue\CategoriesRestApi\CategoriesRestApiFactory getFactory()
 */
class CategoriesRestApiResource extends AbstractRestResource implements CategoriesRestApiResourceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $abstractProductId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findCategoriesByAbstractProductSku(string $abstractProductId, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->getFactory()
            ->createCategoriesReader()
            ->findProductCategoriesBySku($abstractProductId, $restRequest);
    }
}
