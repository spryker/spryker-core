<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\WebProfiler\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\RequestDataCollector as SymfonyRequestDataCollector;

class RequestDataCollector extends SymfonyRequestDataCollector
{
    /**
     * @return array|bool
     */
    public function getRedirect()
    {
        return isset($this->data['redirect']) ? $this->data['redirect'] : false;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return (isset($this->data['method'])) ? $this->data['method'] : 'undefined';
    }
}
