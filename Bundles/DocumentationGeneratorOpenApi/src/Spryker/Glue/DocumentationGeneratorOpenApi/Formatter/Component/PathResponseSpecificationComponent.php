<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Component;

use Symfony\Component\HttpFoundation\Response;

/**
 * Specification:
 *  - This component describes a single response from an API Operation.
 *  - This component covers Operation Object in OpenAPI specification format (see https://swagger.io/specification/#operationObject).
 */
class PathResponseSpecificationComponent implements PathResponseSpecificationComponentInterface
{
    /**
     * @var string
     */
    protected const DESCRIPTION_DEFAULT_RESPONSE = 'Expected response to a bad request.';

    /**
     * @var string
     */
    protected const DESCRIPTION_SUCCESSFUL_RESPONSE = 'Expected response to a valid request.';

    /**
     * @var string
     */
    protected const KEY_DEFAULT = 'default';

    /**
     * @var string
     */
    protected const KEY_APPLICATION_JSON = 'application/json';

    /**
     * @var string
     */
    protected const KEY_CONTENT = 'content';

    /**
     * @var string
     */
    protected const KEY_REF = '$ref';

    /**
     * @var string
     */
    protected const KEY_SCHEMA = 'schema';

    /**
     * @var string
     */
    protected const KEY_DESCRIPTION = 'description';

    /**
     * @var string
     */
    protected const KEY_RESPONSES = 'responses';

    /**
     * @var string
     */
    protected const KEY_DEFAULT_RESPONSE_CODE = 'defaultResponseCode';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'RestErrorMessage';

    /**
     * @var string
     */
    protected const DEFAULT_RESPONSE = 'default';

    /**
     * @var string
     */
    protected const PATTERN_SCHEMA_REFERENCE = '#/components/schemas/%s';

    /**
     * @param array<mixed> $pathMethodData
     * @param string $responseReference
     *
     * @return array<mixed>
     */
    public function getSpecificationComponentData(array $pathMethodData, string $responseReference): array
    {
        $responseComponentData = [];
        $pathMethodData = $this->addDefaultSuccessResponseToResponseData($pathMethodData);
        $pathMethodData = $this->addDefaultErrorResponseToResponseData($pathMethodData);

        foreach ($pathMethodData[static::KEY_RESPONSES] as $code => $description) {
            $responseData = [];
            $responseData[static::KEY_DESCRIPTION] = $description;

            if ((int)$code >= Response::HTTP_BAD_REQUEST || $code === static::DEFAULT_RESPONSE) {
                $responseReference = sprintf(static::PATTERN_SCHEMA_REFERENCE, static::ERROR_MESSAGE);
            }

            if ($responseReference) {
                $responseData[static::KEY_CONTENT][static::KEY_APPLICATION_JSON][static::KEY_SCHEMA][static::KEY_REF] = $responseReference;
            }

            $responseComponentData[$code] = $responseData;
        }

        ksort($responseComponentData, SORT_NATURAL);

        return $responseComponentData;
    }

    /**
     * @param array<mixed> $pathMethodData
     *
     * @return array<mixed>
     */
    protected function addDefaultSuccessResponseToResponseData(array $pathMethodData): array
    {
        if (!isset($pathMethodData[static::KEY_RESPONSES][(string)$pathMethodData[static::KEY_DEFAULT_RESPONSE_CODE]])) {
            if (isset($pathMethodData[static::KEY_RESPONSES])) {
                $pathMethodData[static::KEY_RESPONSES] = array_reverse($pathMethodData[static::KEY_RESPONSES], true);
            }
            $pathMethodData[static::KEY_RESPONSES][(string)$pathMethodData[static::KEY_DEFAULT_RESPONSE_CODE]] = static::DESCRIPTION_SUCCESSFUL_RESPONSE;
            $pathMethodData[static::KEY_RESPONSES] = array_reverse($pathMethodData[static::KEY_RESPONSES], true);
        }

        return $pathMethodData;
    }

    /**
     * @param array<mixed> $pathMethodData
     *
     * @return array<mixed>
     */
    protected function addDefaultErrorResponseToResponseData(array $pathMethodData): array
    {
        $pathMethodData[static::KEY_RESPONSES][static::KEY_DEFAULT] = static::DESCRIPTION_DEFAULT_RESPONSE;

        return $pathMethodData;
    }
}
