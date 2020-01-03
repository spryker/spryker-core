<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Helper;

class JsonConnection extends AbstractConnection
{
    /**
     * @var array|null
     */
    protected $responseJson;

    /**
     * @return array|null
     */
    public function getResponseJson(): ?array
    {
        return $this->responseJson;
    }

    /**
     * @param array|null $responseJson
     *
     * @return static
     */
    public function setResponseJson(?array $responseJson): self
    {
        $this->responseJson = $responseJson;

        return $this;
    }
}
