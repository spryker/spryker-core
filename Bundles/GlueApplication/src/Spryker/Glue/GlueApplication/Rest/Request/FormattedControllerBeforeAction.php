<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\TerminationAwareBeforeActionPluginInterface;
use Symfony\Component\HttpFoundation\Request;

class FormattedControllerBeforeAction implements FormattedControllerBeforeActionInterface
{
    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormattedControllerBeforeActionPluginInterface>
     */
    protected $formattedControllerBeforeActionPlugins;

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormattedControllerBeforeActionPluginInterface> $formattedControllerBeforeActionPlugins
     */
    public function __construct(array $formattedControllerBeforeActionPlugins)
    {
        $this->formattedControllerBeforeActionPlugins = $formattedControllerBeforeActionPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function beforeAction(Request $request): ?RestErrorMessageTransfer
    {
        foreach ($this->formattedControllerBeforeActionPlugins as $formattedControllerBeforeActionPlugin) {
            $restErrorMessageTransfer = $formattedControllerBeforeActionPlugin->beforeAction($request);
            if (!$restErrorMessageTransfer) {
                continue;
            }

            if ($formattedControllerBeforeActionPlugin instanceof TerminationAwareBeforeActionPluginInterface) {
                return $restErrorMessageTransfer;
            }
        }

        return null;
    }
}
