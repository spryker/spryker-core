<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Application\Communication\Bootstrap\Extension;

use Spryker\Shared\Application\Communication\Application;

interface GlobalTemplateVariableExtensionInterface
{

    /**
     * @param Application $application
     */
    public function getGlobalTemplateVariables(Application $application);

}
