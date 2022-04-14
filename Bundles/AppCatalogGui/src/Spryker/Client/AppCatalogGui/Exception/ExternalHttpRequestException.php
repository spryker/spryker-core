<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AppCatalogGui\Exception;

use RuntimeException;

class ExternalHttpRequestException extends RuntimeException
{
    /**
     * @var string|null
     */
    protected $responseBody;

    /**
     * @return string|null
     */
    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    /**
     * @param string|null $responseBody
     *
     * @return $this
     */
    public function setResponseBody(?string $responseBody)
    {
        $this->responseBody = $responseBody;

        return $this;
    }
}
