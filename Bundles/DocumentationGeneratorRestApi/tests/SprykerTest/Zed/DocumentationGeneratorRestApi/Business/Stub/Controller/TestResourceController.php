<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Controller;

class TestResourceController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Summary example"
     *          ],
     *          "parameters": [{
     *              "name": "Accept-Language",
     *              "in": "header"
     *          }],
     *          "responses": {
     *              "400": "Bad Request",
     *              "404": "Item not found"
     *          }
     *     }
     * })
     *
     * @return void
     */
    public function getAction(): void
    {
    }

    /**
     * @Glue({
     *     "post": {
     *          "responseAttributesClassName": "SprykerTest\\Zed\\DocumentationGeneratorRestApi\\Business\\Stub\\RestTestAlternativeAttributesTransfer",
     *          "responses": {
     *              "400": "Bad Request"
     *          }
     *     }
     * })
     *
     * @return void
     */
    public function postAction(): void
    {
    }
}
