<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Controller;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\Controller\AbstractController;

/**
 * @method \Spryker\Glue\ConfigurableBundlesRestApi\ConfigurableBundlesRestApiFactory getFactory()
 */
class ConfigurableBundleTemplatesResourceController extends AbstractController
{

    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves ConfigurableBundleTemplates data by id."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }],
     *          "responses": {
     *              "404": "Not found."
     *          }
     *     },
     *     "getCollection": {
     *          "summary": [
     *              "Retrieves collection of ConfigurableBundleTemplates."
     *          ],
     *          "parameters": [{
     *              "ref": "acceptLanguage"
     *          }]
     *     }
     * })
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAction(RestRequestInterface $restRequest): RestResponseInterface
    {
        $idConfigurableBundleTemplate = $restRequest->getResource()->getId();

        if ($idConfigurableBundleTemplate) {
            return $this->getFactory()
                ->createConfigurableBundleTemplateReader()
                ->getConfigurableBundleTemplate($idConfigurableBundleTemplate, $restRequest);
        }

        return $this->getFactory()
            ->createConfigurableBundleTemplateReader()
            ->getConfigurableBundleTemplateCollection($restRequest);
    }
}
