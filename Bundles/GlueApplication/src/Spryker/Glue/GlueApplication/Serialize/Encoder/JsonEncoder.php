<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Serialize\Encoder;

use Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceInterface;

/**
 * @deprecated Will be removed without replacement.
 */
class JsonEncoder implements EncoderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(GlueApplicationToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return string
     */
    public function encode(array $data): string
    {
        if (!$data) {
            return '';
        }

        return (string)$this->utilEncodingService->encodeJson($data);
    }
}
