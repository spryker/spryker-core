<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business\Stub\Controller;

class TestResourceController
{
    /**
     * @Glue({
     *     "getResource": {
     *          "summary": "Summary example",
     *          "headers": [
     *              "Accept-Language"
     *          ],
     *          "responses": {
     *              "400": "Bad Request",
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
     *          "responseClass": "Generated\\Shared\\Transfer\\RestTokenResponseAttributesTransfer",
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
}
