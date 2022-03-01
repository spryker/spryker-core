<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Response;

class ResponseSparseFieldFormatter implements ResponseSparseFieldFormatterInterface
{
    /**
     * @var string
     */
    protected const RESPONSE_INCLUDED = 'included';

    /**
     * @var string
     */
    protected const RESPONSE_DATA = 'data';

    /**
     * @var string
     */
    protected const RESOURCE_TYPE = 'type';

    /**
     * @var string
     */
    protected const RESOURCE_ATTRIBUTES = 'attributes';

    /**
     * @param array<string, mixed> $sparseFields
     * @param array<string, mixed> $responseData
     * @param string|null $resourceId
     *
     * @return array<string, mixed>
     */
    public function format(array $sparseFields, array $responseData, ?string $resourceId): array
    {
        if (isset($responseData[static::RESPONSE_INCLUDED])) {
            $responseData[static::RESPONSE_INCLUDED] = $this->formatCollectionData($sparseFields, $responseData[static::RESPONSE_INCLUDED]);
        }
        if ($resourceId) {
            $responseData[static::RESPONSE_DATA] = $this->formatResponseData($sparseFields, $responseData[static::RESPONSE_DATA]);

            return $responseData;
        }

        $responseData[static::RESPONSE_DATA] = $this->formatCollectionData($sparseFields, $responseData[static::RESPONSE_DATA]);

        return $responseData;
    }

    /**
     * @param array<string, mixed> $sparseFields
     * @param array<string, mixed> $responseField
     *
     * @return array<string, mixed>
     */
    protected function formatResponseData(array $sparseFields, array $responseField): array
    {
        if (array_key_exists($responseField[static::RESOURCE_TYPE], $sparseFields)) {
            $attributes = $responseField[static::RESOURCE_ATTRIBUTES];
            $resourceAttributes = [];
            foreach ($sparseFields[$responseField[static::RESOURCE_TYPE]] as $sparseField) {
                if (isset($attributes[$sparseField])) {
                    $resourceAttributes[$sparseField] = $attributes[$sparseField];
                }
            }
            $responseField[static::RESOURCE_ATTRIBUTES] = $resourceAttributes;
        }

        return $responseField;
    }

    /**
     * @param array<string, mixed> $sparseFields
     * @param array<string, mixed> $responseData
     *
     * @return array<int, mixed>
     */
    protected function formatCollectionData(array $sparseFields, array $responseData): array
    {
        $formattedResponseData = [];
        foreach ($responseData as $responseField) {
            $formattedResponseData[] = $this->formatResponseData($sparseFields, $responseField);
        }

        return $formattedResponseData;
    }
}
