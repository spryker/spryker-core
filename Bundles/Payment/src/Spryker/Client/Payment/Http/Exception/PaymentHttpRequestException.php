<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payment\Http\Exception;

use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class PaymentHttpRequestException extends RuntimeException
{
    /**
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        $previous = $this->getPrevious();

        if (!$previous || !method_exists($previous, 'getResponse')) {
            return null;
        }

        return $previous->getResponse();
    }
}
