<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HttpJsonResponseBuilder implements HttpResponseBuilderInterface
{
    /**
     * @param mixed[] $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function buildResponse(array $data): Response
    {
        return new JsonResponse($data);
    }
}
