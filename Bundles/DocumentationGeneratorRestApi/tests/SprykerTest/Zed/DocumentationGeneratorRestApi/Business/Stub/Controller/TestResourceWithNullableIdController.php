<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Stub\Controller;

class TestResourceWithNullableIdController
{
    /**
     * @Glue({
     *     "getCollection": {
     *          "isIdNullable": true
     *     }
     * })
     *
     * @return void
     */
    public function getAction()
    {
    }
}
