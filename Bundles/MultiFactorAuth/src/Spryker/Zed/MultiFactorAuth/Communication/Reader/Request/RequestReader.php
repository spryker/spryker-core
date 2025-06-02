<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Reader\Request;

use Symfony\Component\HttpFoundation\Request;

class RequestReader implements RequestReaderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $parameter
     * @param string|null $formName
     *
     * @return mixed
     */
    public function get(Request $request, string $parameter, ?string $formName = null)
    {
        $data = json_decode((string)$request->getContent(), true);

        if (isset($data[$parameter])) {
            return $data[$parameter];
        }

        if ($request->query->has($parameter)) {
            return $request->query->get($parameter);
        }

        if ($formName !== null && isset($request->request->all($formName)[$parameter])) {
            return $request->request->all($formName)[$parameter];
        }

        return $request->request->get($parameter) ?? null;
    }
}
