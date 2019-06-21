<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Resolver;

use Spryker\Zed\SetupFrontend\SetupFrontendConfig;

class BuilderCommandResolver implements BuilderCommandResolverInterface
{
    protected const STORE_NAME_KEY = '%store%';

    /**
     * @var \Spryker\Zed\SetupFrontend\SetupFrontendConfig
     */
    protected $setupFrontendConfig;

    /**
     * @var string
     */
    protected $storeName;

    /**
     * @param \Spryker\Zed\SetupFrontend\SetupFrontendConfig $setupFrontendConfig
     * @param string $storeName
     */
    public function __construct(
        SetupFrontendConfig $setupFrontendConfig,
        string $storeName
    ) {
        $this->setupFrontendConfig = $setupFrontendConfig;
        $this->storeName = $storeName;
    }

    /**
     * @return string
     */
    public function getYvesBuildCommand(): string
    {
        return str_replace(
            static::STORE_NAME_KEY,
            $this->storeName,
            $this->setupFrontendConfig->getYvesBuildCommand()
        );
    }
}
