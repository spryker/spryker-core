<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueJsonApiConvention\Decoder;

use Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceInterface;

class JsonDecoder implements DecoderInterface
{
    /**
     * @var \Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(GlueJsonApiConventionToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $data
     *
     * @return array<mixed>
     */
    public function decode(string $data): array
    {
        $decodedData = $this->utilEncodingService->decodeJson($data, true);
        if (!$decodedData) {
            return [];
        }

        return $decodedData;
    }
}
