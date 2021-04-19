<?php

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Symfony\Component\HttpFoundation\Request;

interface FormattedControllerBeforeActionTerminatePluginInterface
{
    /**
     * @api
     *
     * Specification:
     *  - Validates HTTP request before further processing, terminates on first failure. If null returned proceeds to other terminate plugin.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function terminate(Request $request): ?RestErrorMessageTransfer;
}
