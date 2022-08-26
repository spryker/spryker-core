<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business;

use Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\Processor\ProtectedPathAuthorization\Checker\ProtectedPathAuthorizationChecker;
use Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\Processor\ProtectedPathAuthorization\Checker\ProtectedPathAuthorizationCheckerInterface;
use Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\Processor\ProtectedPathAuthorization\Expander\ProtectedPathAuthorizationExpander;
use Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\Processor\ProtectedPathAuthorization\Expander\ProtectedPathAuthorizationExpanderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\GlueBackendApiApplicationAuthorizationConnectorConfig getConfig()
 */
class GlueBackendApiApplicationAuthorizationConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\Processor\ProtectedPathAuthorization\Checker\ProtectedPathAuthorizationCheckerInterface
     */
    public function createProtectedPathAuthorizationChecker(): ProtectedPathAuthorizationCheckerInterface
    {
        return new ProtectedPathAuthorizationChecker($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\GlueBackendApiApplicationAuthorizationConnector\Business\Processor\ProtectedPathAuthorization\Expander\ProtectedPathAuthorizationExpanderInterface
     */
    public function createProtectedPathAuthorizationExpander(): ProtectedPathAuthorizationExpanderInterface
    {
        return new ProtectedPathAuthorizationExpander($this->createProtectedPathAuthorizationChecker());
    }
}
