<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaginationParametersRequestValidator implements PaginationParametersRequestValidatorInterface
{
    protected const PATTERN_REGEX_PAGE_PARAMETER = '/^\d+$/';

    protected const EXCEPTION_MESSAGE_INVALID_PAGE_PARAMETERS = 'Pagination parameters are invalid.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $request): ?RestErrorMessageTransfer
    {
        $queryParameters = $request->query->all();
        $queryPage = $queryParameters[RequestConstantsInterface::QUERY_PAGE];

        $offset = $queryPage[RequestConstantsInterface::QUERY_OFFSET] ?? null;
        $limit = $queryPage[RequestConstantsInterface::QUERY_LIMIT] ?? null;

        if ($offset === null || $limit === null) {
            return null;
        }

        if (!$this->validatePageParameters($offset, $limit)) {
            return (new RestErrorMessageTransfer())
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(static::EXCEPTION_MESSAGE_INVALID_PAGE_PARAMETERS);
        }

        return null;
    }

    /**
     * @param string $offset
     * @param string $limit
     *
     * @return bool
     */
    protected function validatePageParameters(string $offset, string $limit): bool
    {
        if ($offset && !preg_match(static::PATTERN_REGEX_PAGE_PARAMETER, $offset)) {
            return false;
        }

        if ($limit && !preg_match(static::PATTERN_REGEX_PAGE_PARAMETER, $limit)) {
            return false;
        }

        if ($limit <= 0) {
            return false;
        }

        return true;
    }
}
