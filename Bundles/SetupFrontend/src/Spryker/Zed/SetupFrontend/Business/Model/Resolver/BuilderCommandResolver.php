<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Resolver;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\SetupFrontend\SetupFrontendConfig;

class BuilderCommandResolver implements BuilderCommandResolverInterface
{
    protected const STORE_KEY = '%store%';

    /**
     * @var \Spryker\Zed\SetupFrontend\SetupFrontendConfig
     */
    protected $setupFrontendConfig;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Zed\SetupFrontend\SetupFrontendConfig $setupFrontendConfig
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        SetupFrontendConfig $setupFrontendConfig,
        Store $store
    ) {
        $this->setupFrontendConfig = $setupFrontendConfig;
        $this->store = $store;
    }

    /**
     * @return string
     */
    public function getYvesBuildCommand(): string
    {
        return str_replace(
            static::STORE_KEY,
            strtolower($this->store->getStoreName()),
            $this->setupFrontendConfig->getYvesBuildCommand()
        );
    }
}
