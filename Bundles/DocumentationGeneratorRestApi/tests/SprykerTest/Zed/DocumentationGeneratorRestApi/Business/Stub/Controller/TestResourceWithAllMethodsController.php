<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Controller;

class TestResourceWithAllMethodsController
{
    /**
     * @Glue({
     *     "getResource": {
     *          "summary": [
     *              "getResource summary"
     *          ],
     *          "headers": [
     *              "Accept-Language"
     *          ],
     *          "responses": {
     *              "404": "Item not found"
     *          }
     *     }
     * })
     *
     * @return void
     */
    public function getAction()
    {
    }

    /**
     * @Glue({
     *     "post": {
     *          "summary": [
     *              "post summary"
     *          ],
     *          "responses": {
     *              "400": "Bad Request",
     *              "500": "Server Error"
     *          }
     *     }
     * })
     *
     * @return void
     */
    public function postAction()
    {
    }

    /**
     * @Glue({
     *     "patch": {
     *          "summary": [
     *              "patch summary"
     *          ],
     *          "responses": {
     *              "404": "Item not found",
     *              "500": "Server Error"
     *          }
     *     }
     * })
     *
     * @return void
     */
    public function patchAction()
    {
    }

    /**
     * @Glue({
     *     "delete": {
     *          "summary": [
     *              "patch summary"
     *          ],
     *          "responses": {
     *              "404": "Item not found",
     *              "500": "Server Error"
     *          }
     *     }
     * })
     *
     * @return void
     */
    public function deleteAction()
    {
    }
}
