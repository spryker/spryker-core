<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Controller;

use Generated\Shared\Transfer\CategoriesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController;

/**
 * @method \Spryker\Glue\CategoriesBackendApi\CategoriesBackendApiFactory getFactory()
 */
class CategoriesResourceController extends AbstractController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieve category by categoryKey."
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\CategoriesBackendApiAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request.",
     *              "403": "Unauthorized request.",
     *              "404": "Not Found."
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
        return $this->getFactory()
            ->createCategoryReader()
            ->getCategory($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Retrieve category collection."
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\CategoriesBackendApiAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request.",
     *              "403": "Unauthorized request.",
     *              "404": "Not Found."
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
        return $this->getFactory()
            ->createCategoryReader()
            ->getCategoryCollection($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Update category."
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\CategoriesBackendApiAttributesTransfer",
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\CategoriesBackendApiAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request.",
     *              "403": "Unauthorized request.",
     *              "404": "Not Found."
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\CategoriesBackendApiAttributesTransfer $categoriesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function patchAction(
        CategoriesBackendApiAttributesTransfer $categoriesBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createCategoryUpdater()
            ->updateCategory($categoriesBackendApiAttributesTransfer, $glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Delete category by categoryKey."
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\CategoriesBackendApiAttributesTransfer",
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\CategoriesBackendApiAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request.",
     *              "403": "Unauthorized request.",
     *              "404": "Not Found."
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function deleteAction(
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()->createCategoryDeleter()->deleteCategories($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Create category."
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\CategoriesBackendApiAttributesTransfer",
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\CategoriesBackendApiAttributesTransfer",
     *          "responses": {
     *              "400": "Bad request.",
     *              "403": "Unauthorized request.",
     *              "404": "Not Found."
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\CategoriesBackendApiAttributesTransfer $categoriesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(
        CategoriesBackendApiAttributesTransfer $categoriesBackendApiAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createCategoryCreator()
            ->createCategory($categoriesBackendApiAttributesTransfer, $glueRequestTransfer);
    }
}
