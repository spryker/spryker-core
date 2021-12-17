<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\ApiApplication;

use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAgnosticApiApplication;
use Spryker\Glue\GlueApplication\Exception\UnknownRequestFlowImplementationException;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface;
use Spryker\Shared\Application\ApplicationInterface;

class ApiApplicationProxy implements ApplicationInterface
{
    protected GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin
     */
    public function __construct(
        GlueApplicationBootstrapPluginInterface $glueApplicationBootstrapPlugin
    ) {
        $this->glueApplicationBootstrapPlugin = $glueApplicationBootstrapPlugin;
    }

    /**
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function boot(): ApplicationInterface
    {
        $this->glueApplicationBootstrapPlugin->getApplication()->boot();

        return $this;
    }

    /**
     * @throws \Spryker\Glue\GlueApplication\Exception\UnknownRequestFlowImplementationException
     *
     * @return void
     */
    public function run(): void
    {
        $bootstrapApplication = $this->glueApplicationBootstrapPlugin->getApplication();

        if ($bootstrapApplication instanceof RequestFlowAgnosticApiApplication) {
            $bootstrapApplication->run();

            return;
        }

        throw new UnknownRequestFlowImplementationException(sprintf(
            '%s needs to implement either %s',
            get_class($bootstrapApplication),
            RequestFlowAgnosticApiApplication::class,
        ));
    }
}
