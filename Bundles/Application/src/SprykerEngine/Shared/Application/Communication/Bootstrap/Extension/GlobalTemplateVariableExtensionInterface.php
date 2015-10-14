<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerEngine\Shared\Application\Communication\Bootstrap\Extension;

use SprykerEngine\Shared\Application\Communication\Application;

interface GlobalTemplateVariableExtensionInterface
{

    /**
     * @param Application $application
     */
    public function getGlobalTemplateVariables(Application $application);

}
