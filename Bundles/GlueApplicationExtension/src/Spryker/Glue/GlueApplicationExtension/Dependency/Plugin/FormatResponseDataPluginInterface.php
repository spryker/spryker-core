<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface FormatResponseDataPluginInterface
{
    /**
     * @api
     *
     * Specification:
     *  - Format/edit response data as in http body, preparedResponseData is the array you need to modify.
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $request
     * @param array $preparedResponseData
     *
     * @return array
     */
    public function format(RestRequestInterface $request, array $preparedResponseData): array;
}
