<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\KeyProvider;

use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockKeyNotCreatedException;
use Spryker\Zed\CmsBlock\Dependency\Service\CmsBlockToUtilUuidGeneratorServiceInterface;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface;

class CmsBlockKeyProvider implements CmsBlockKeyProviderInterface
{
    protected const KEY_GENERATOR_ITERATION_LIMIT = 10;
    protected const ERROR_CONTENT_KEY_NOT_CREATED = 'Cannot create cms block key: maximum iterations threshold met.';

    /**
     * @var \Spryker\Zed\CmsBlock\Dependency\Service\CmsBlockToUtilUuidGeneratorServiceInterface
     */
    protected $utilUuidGeneratorService;

    /**
     * @var \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface
     */
    protected $cmsBlockRepository;

    /**
     * @param \Spryker\Zed\CmsBlock\Dependency\Service\CmsBlockToUtilUuidGeneratorServiceInterface $utilUuidGeneratorService
     * @param \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface $cmsBlockRepository
     */
    public function __construct(
        CmsBlockToUtilUuidGeneratorServiceInterface $utilUuidGeneratorService,
        CmsBlockRepositoryInterface $cmsBlockRepository
    ) {
        $this->utilUuidGeneratorService = $utilUuidGeneratorService;
        $this->cmsBlockRepository = $cmsBlockRepository;
    }

    /**
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockKeyNotCreatedException
     *
     * @return string
     */
    public function generateKey(): string
    {
        $index = 0;

        do {
            if ($index >= static::KEY_GENERATOR_ITERATION_LIMIT) {
                throw new CmsBlockKeyNotCreatedException(static::ERROR_CONTENT_KEY_NOT_CREATED);
            }

            $candidate = $this->suggestCandidate($index);
            $index++;
        } while ($this->isCandidateSuitable($candidate));

        return $candidate;
    }

    /**
     * @param int $index
     *
     * @return string
     */
    protected function suggestCandidate(int $index): string
    {
        return $this->utilUuidGeneratorService->generateUuid5FromObjectId(
            sprintf("%s-%d", microtime(true), $index)
        );
    }

    /**
     * @param string $candidate
     *
     * @return bool
     */
    protected function isCandidateSuitable(string $candidate): bool
    {
        return $this->cmsBlockRepository->hasKey($candidate);
    }
}
