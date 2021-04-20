<?php

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * Implementations are used in {@link \Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider::getFormattedControllerBeforeActionTerminatePlugins()} for processing some actions before executing controllers that extends {@link \Spryker\Glue\Kernel\Controller\FormattedAbstractController}.
 */
interface FormattedControllerBeforeActionTerminatePluginInterface
{
    /**
     * @api
     *
     * Specification:
     *  - Executes some action before further processing of controller. If null returned proceeds to other plugin.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function beforeAction(Request $request): ?RestErrorMessageTransfer;
}
