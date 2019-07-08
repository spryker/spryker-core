<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Content\Dependency\Service;

class ContentToUtilUuidGeneratorServiceBridge implements ContentToUtilUuidGeneratorServiceInterface
{
    /**
     * @var \Spryker\Service\UtilUuidGenerator\UtilUuidGeneratorServiceInterface
     */
    protected $utilUuidGeneratorService;

    /**
     * @param \Spryker\Service\UtilUuidGenerator\UtilUuidGeneratorServiceInterface $utilUuidGeneratorService
     */
    public function __construct($utilUuidGeneratorService)
    {
        $this->utilUuidGeneratorService = $utilUuidGeneratorService;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function generateUuid5FromObjectId(string $name): string
    {
        return $this->utilUuidGeneratorService->generateUuid5FromObjectId($name);
    }
}
