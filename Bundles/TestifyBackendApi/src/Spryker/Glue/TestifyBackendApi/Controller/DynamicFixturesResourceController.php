<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Controller;

use Generated\Shared\Transfer\DynamicFixturesRequestBackendApiAttributesTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\Kernel\Backend\Controller\AbstractController;

/**
 * @method \Spryker\Glue\TestifyBackendApi\TestifyBackendApiFactory getFactory()
 * @method \Spryker\Glue\TestifyBackendApi\TestifyBackendApiFactory getConfig()
 */
class DynamicFixturesResourceController extends AbstractController
{
    /**
     * @Glue({
     *       "post": {
     *           "summary": [
     *               "Generates the dynamicFixtures by using the provided request payload."
     *           ],
     *           "parameters": [
     *                {
     *                    "ref": "acceptLanguage"
     *                },
     *                {
     *                    "ref": "ContentType"
     *                }
     *            ],
     *           "requestAttributesClassName": "Generated\\Shared\\Transfer\\DynamicFixturesRequestBackendApiAttributesTransfer",
     *           "responseAttributesClassName": "Generated\\Shared\\Transfer\\DynamicFixturesBackendApiAttributesTransfer",
     *           "responses": {
     *               "400": "Bad Request"
     *           }
     *      }
     *  })
     *
     * @param \Generated\Shared\Transfer\DynamicFixturesRequestBackendApiAttributesTransfer $dynamicFixturesRequestBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function postAction(
        DynamicFixturesRequestBackendApiAttributesTransfer $dynamicFixturesRequestBackendApiAttributesTransfer
    ): GlueResponseTransfer {
        return $this->getFactory()
            ->createDynamicFixtureGenerator()
            ->generate($dynamicFixturesRequestBackendApiAttributesTransfer);
    }
}
