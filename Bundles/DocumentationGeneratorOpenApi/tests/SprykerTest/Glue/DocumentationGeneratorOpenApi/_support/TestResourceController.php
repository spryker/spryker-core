<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DocumentationGeneratorOpenApi;

class TestResourceController
{
    /**
     * @Glue({
     *      "getCollection": {
     *          "summary": [
     *              "Retrieves collection of stores."
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestAttributesTransfer"
     *      }
     * })
     *
     * @return void
     */
    public function getEmptyResponseAction(): void
    {
    }

    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves store by id."
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestAttributesTransfer",
     *          "responses": {
     *              "404": "Store not found."
     *          }
     *     }
     * })
     *
     * @return void
     */
    public function getAction(): void
    {
    }
}
