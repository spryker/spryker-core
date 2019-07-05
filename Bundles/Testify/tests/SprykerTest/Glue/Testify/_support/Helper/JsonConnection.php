<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
