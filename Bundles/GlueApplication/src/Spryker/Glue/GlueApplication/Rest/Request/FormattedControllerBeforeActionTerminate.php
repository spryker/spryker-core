<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\TerminationAwareBeforeActionPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class FormattedControllerBeforeActionTerminate implements FormattedControllerBeforeActionTerminateInterface
{
    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormattedControllerBeforeActionTerminatePluginInterface[]
     */
    protected $formattedControllerBeforeActionTerminatePlugins;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormattedControllerBeforeActionTerminatePluginInterface[] $formattedControllerBeforeActionTerminatePlugins
     */
    public function __construct(array $formattedControllerBeforeActionTerminatePlugins)
    {
        $this->formattedControllerBeforeActionTerminatePlugins = $formattedControllerBeforeActionTerminatePlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function beforeAction(Request $request): ?RestErrorMessageTransfer
    {
        foreach ($this->formattedControllerBeforeActionTerminatePlugins as $formattedControllerBeforeActionHttpRequestsValidatorPlugin) {
            $restErrorMessageTransfer = $formattedControllerBeforeActionHttpRequestsValidatorPlugin->beforeAction($request);
            if (!$restErrorMessageTransfer) {
                continue;
            }

            if ($formattedControllerBeforeActionHttpRequestsValidatorPlugin instanceof TerminationAwareBeforeActionPluginInterface && $formattedControllerBeforeActionHttpRequestsValidatorPlugin->terminateOnFailure()) {
                return $restErrorMessageTransfer;
            }
        }

        return null;
    }
}
