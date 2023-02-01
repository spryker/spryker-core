<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Controller;

use Generated\Shared\Transfer\ApiProductsAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ProductsBackendApi\ProductsBackendApiFactory getFactory()
 */
class ProductsResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves product abstract collection."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiProductsAttributesTransfer",
     *          "responses": {
     *              "404": "Not Found",
     *              "400": "Conflict",
     *              "404": "Bad Request"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getCollectionAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()->createProductAbstractReader()->getProductAbstractCollection($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves product abstract by SKU."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiProductsAttributesTransfer",
     *          "responses": {
     *              "404": "Not Found",
     *              "400": "Conflict",
     *              "404": "Bad Request"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()->createProductAbstractReader()->getProductAbstract($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates product abstract."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiProductsAttributesTransfer",
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\ApiProductsAttributesTransfer",
     *          "responses": {
     *              "404": "Not Found",
     *              "400": "Conflict",
     *              "404": "Bad Request"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\ApiProductsAttributesTransfer $apiProductsAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(
        ApiProductsAttributesTransfer $apiProductsAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()->createProductAbstractCreator()->createProductAbstract($apiProductsAttributesTransfer, $glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates product abstract."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiProductsAttributesTransfer",
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\ApiProductsAttributesTransfer",
     *          "responses": {
     *              "404": "Not Found",
     *              "400": "Conflict",
     *              "404": "Bad Request"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\ApiProductsAttributesTransfer $apiProductsAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function patchAction(
        ApiProductsAttributesTransfer $apiProductsAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()->createProductAbstractUpdater()->updateProductAbstract($apiProductsAttributesTransfer, $glueRequestTransfer);
    }
}
