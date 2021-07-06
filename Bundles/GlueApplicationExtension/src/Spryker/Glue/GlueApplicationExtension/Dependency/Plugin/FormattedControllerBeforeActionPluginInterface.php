<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * Implementations are used in {@link \Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider::getFormattedControllerBeforeActionTerminatePlugins()} for processing some actions before executing controllers that extend {@link \Spryker\Glue\Kernel\Controller\FormattedAbstractController}.
 */
interface FormattedControllerBeforeActionPluginInterface
{
    /**
     * @api
     *
     * Specification:
     *  - Executes before calling the action on {@link \Spryker\Glue\Kernel\Controller\FormattedAbstractController}. If null returned proceeds to other plugins.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function beforeAction(Request $request): ?RestErrorMessageTransfer;
}
