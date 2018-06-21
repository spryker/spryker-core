<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Serialize\Decoder;

use Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceInterface;

class JsonDecoder implements DecoderInterface
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
     * @param string $data
     *
     * @return array
     */
    public function decode($data): array
    {
        return $this->utilEncodingService->decodeJson($data, true);
    }
}
