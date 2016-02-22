<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

interface AbstractZedClientInterface
{

    /**
     * @param string $name
     * @param mixed $metaTransfer
     *
     * @return $this
     */
    public function addMetaTransfer($name, $metaTransfer);

    /**
     * @throws \BadMethodCallException
     *
     * @return \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    public function getLastResponse();

}
