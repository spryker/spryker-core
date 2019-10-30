<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartNote\Zed;

use Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToZedRequestClientInterface;

class ConfigurableBundleCartNoteStub implements ConfigurableBundleCartNoteStubInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ConfigurableBundleCartNote\Dependency\Client\ConfigurableBundleCartNoteToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ConfigurableBundleCartNoteToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }
}
