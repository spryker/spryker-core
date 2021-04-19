<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Symfony\Component\HttpFoundation\Request;

class FormattedControllerBeforeActionHttpRequestValidator implements FormattedControllerBeforeActionHttpRequestValidatorInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[]
     */
    protected $formattedControllerBeforeActionHttpRequestsValidatorPlugins;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[] $formattedControllerBeforeActionHttpRequestsValidatorPlugins
     */
    public function __construct(array $formattedControllerBeforeActionHttpRequestsValidatorPlugins)
    {
        $this->formattedControllerBeforeActionHttpRequestsValidatorPlugins = $formattedControllerBeforeActionHttpRequestsValidatorPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $request): ?RestErrorMessageTransfer
    {
        foreach ($this->formattedControllerBeforeActionHttpRequestsValidatorPlugins as $formattedControllerBeforeActionHttpRequestsValidatorPlugin) {
            $restErrorMessageTransfer = $formattedControllerBeforeActionHttpRequestsValidatorPlugin->validate($request);
            if (!$restErrorMessageTransfer) {
                continue;
            }

            return $restErrorMessageTransfer;
        }

        return null;
    }
}
