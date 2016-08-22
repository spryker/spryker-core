<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

abstract class AbstractClientProvider
{

    /**
     * @var mixed
     */
    protected $client;

    /**
     * @return mixed
     */
    public function getInstance()
    {
        if ($this->client === null) {
            $this->client = $this->createZedClient();
        }

        return $this->client;
    }

    /**
     * @return mixed
     */
    abstract protected function createZedClient();

}
