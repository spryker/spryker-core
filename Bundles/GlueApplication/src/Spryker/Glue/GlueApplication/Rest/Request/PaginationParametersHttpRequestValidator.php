<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaginationParametersHttpRequestValidator implements PaginationParametersHttpRequestValidatorInterface
{
    protected const ERROR_MESSAGE_INVALID_PAGE_PARAMETERS = 'Pagination parameters are invalid.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $request): ?RestErrorMessageTransfer
    {
        $queryParameters = $request->query->all();
        $queryPage = $queryParameters[RequestConstantsInterface::QUERY_PAGE] ?? null;

        if ($queryPage === null) {
            return null;
        }

        $offset = $queryPage[RequestConstantsInterface::QUERY_OFFSET] ?? null;
        $limit = $queryPage[RequestConstantsInterface::QUERY_LIMIT] ?? null;

        if ($offset === null && $limit === null) {
            return null;
        }

        if (!$this->checkParameters($offset, $limit)) {
            return (new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(static::ERROR_MESSAGE_INVALID_PAGE_PARAMETERS);
        }

        return null;
    }

    /**
     * @param string|null $offset
     * @param string|null $limit
     *
     * @return bool
     */
    protected function checkParameters(?string $offset, ?string $limit): bool
    {
        if ($offset && !filter_var($offset, FILTER_VALIDATE_INT)) {
            return false;
        }

        if ($limit && !filter_var($limit, FILTER_VALIDATE_INT)) {
            return false;
        }

        if ($limit <= 0) {
            return false;
        }

        return true;
    }
}
