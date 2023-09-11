<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Api\Decoder;

use Psr\Http\Message\ResponseInterface;
use Spryker\Client\SearchHttp\Dependency\Service\SearchHttpToUtilEncodingServiceInterface;
use Spryker\Client\SearchHttp\Exception\SearchResponseException;

class SearchResponseDecoder implements SearchResponseDecoderInterface
{
    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Service\SearchHttpToUtilEncodingServiceInterface
     */
    protected SearchHttpToUtilEncodingServiceInterface $searchHttpToUtilEncodingService;

    /**
     * @param \Spryker\Client\SearchHttp\Dependency\Service\SearchHttpToUtilEncodingServiceInterface $searchHttpToUtilEncodingService
     */
    public function __construct(
        SearchHttpToUtilEncodingServiceInterface $searchHttpToUtilEncodingService
    ) {
        $this->searchHttpToUtilEncodingService = $searchHttpToUtilEncodingService;
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @throws \Spryker\Client\SearchHttp\Exception\SearchResponseException
     *
     * @return array<string, mixed>
     */
    public function decode(ResponseInterface $response): array
    {
        $responseData = $this->searchHttpToUtilEncodingService->decodeJson($response->getBody()->getContents(), true);

        if ($responseData === null) {
            throw new SearchResponseException('Wrong response format from Search API. Not a JSON or corrupted JSON.');
        }

        if (!$responseData || !is_array($responseData)) {
            throw new SearchResponseException('Response data from Search API is empty or invalid');
        }

        return $responseData;
    }
}
