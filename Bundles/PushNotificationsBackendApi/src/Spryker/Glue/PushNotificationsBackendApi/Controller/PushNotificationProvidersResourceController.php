<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi\Controller;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractBackendApiController;

/**
 * @method \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiFactory getFactory()
 */
class PushNotificationProvidersResourceController extends AbstractBackendApiController
{
    /**
     * @Glue({
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves a collection of push notification providers."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "Page"
     *              },
     *              {
     *                  "ref": "Sort"
     *              },
     *              {
     *                  "ref": "Fields"
     *              },
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              }
     *          ],
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\ApiPushNotificationProvidersAttributesTransfer",
     *          "responses": {
     *              "200": "OK",
     *              "400": "Bad Request",
     *              "403": "Unauthorized request"
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
            ->createPushNotificationProviderReader()
            ->getPushNotificationProviderCollection($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "Creates a push notification provider."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              }
     *          ],
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\ApiPushNotificationProvidersAttributesTransfer",
     *          "requestAttributesClassName": "\\Generated\\Shared\\Transfer\\ApiPushNotificationProvidersAttributesTransfer",
     *          "responses": {
     *              "201": "Created",
     *              "400": "Bad Request",
     *              "403": "Unauthorized request"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createPushNotificationProviderCreator()
            ->createPushNotificationProvider($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "get": {
     *          "summary": [
     *              "Retrieves a push notification provider by uuid."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "PushNotificationProviderUuid"
     *              },
     *              {
     *                  "ref": "Fields"
     *              },
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              }
     *          ],
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\ApiPushNotificationProvidersAttributesTransfer",
     *          "responses": {
     *              "200": "OK",
     *              "400": "Bad Request",
     *              "403": "Unauthorized request"
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
            ->createPushNotificationProviderReader()
            ->getPushNotificationProvider($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "Updates a push notification provider."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              },
     *              {
     *                  "ref": "Fields"
     *              }
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\ApiPushNotificationProvidersAttributesTransfer",
     *          "requestAttributesClassName": "Generated\\Shared\\Transfer\\ApiPushNotificationProvidersAttributesTransfer",
     *          "responses": {
     *              "400": "Bad Request",
     *              "403": "Unauthorized request",
     *              "404": "Not Found"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function patchAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createPushNotificationProviderUpdater()
            ->updatePushNotificationProvider($glueRequestTransfer);
    }

    /**
     * @Glue({
     *     "delete": {
     *          "summary": [
     *              "Deletes push notification provider."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "ref": "ContentType"
     *              }
     *          ],
     *          "responseAttributesClassName": "\\Generated\\Shared\\Transfer\\ApiPushNotificationProvidersAttributesTransfer",
     *          "requestAttributesClassName": "\\Generated\\Shared\\Transfer\\ApiPushNotificationProvidersAttributesTransfer",
     *          "responses": {
     *              "204": "No Content",
     *              "400": "Bad Request",
     *              "403": "Unauthorized request"
     *          }
     *     }
     * })
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function deleteAction(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        return $this->getFactory()
            ->createPushNotificationProviderDeleter()
            ->deletePushNotificationProvider($glueRequestTransfer);
    }
}
