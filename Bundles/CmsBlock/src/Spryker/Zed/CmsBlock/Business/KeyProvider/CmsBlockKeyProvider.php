<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\KeyProvider;

use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockKeyNotCreatedException;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface;

class CmsBlockKeyProvider implements CmsBlockKeyProviderInterface
{
    protected const KEY_GENERATOR_PREFIX = 'blck';
    protected const KEY_GENERATOR_ITERATION_LIMIT = 10;

    /**
     * @var \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface
     */
    protected $cmsBlockRepository;

    /**
     * @param \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface $cmsBlockRepository
     */
    public function __construct(CmsBlockRepositoryInterface $cmsBlockRepository)
    {
        $this->cmsBlockRepository = $cmsBlockRepository;
    }

    /**
     * @return string
     */
    public function generateKey(): string
    {
        return $this->getMaxIdBasedKey();
    }

    /**
     * @param int $idCmsBlock
     *
     * @return string
     */
    public function generateKeyByIdCmsBlock(int $idCmsBlock): string
    {
        $keyCandidate = $this->suggestCandidate($idCmsBlock);

        if ($this->isCandidateSuitable($keyCandidate)) {
            return $keyCandidate;
        }

        return $this->getMaxIdBasedKey();
    }

    /**
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockKeyNotCreatedException
     *
     * @return string
     */
    protected function getMaxIdBasedKey(): string
    {
        $index = $this->cmsBlockRepository->findMaxIdCmsBlock() + 1;

        $attempt = 0;
        do {
            if ($attempt >= static::KEY_GENERATOR_ITERATION_LIMIT) {
                throw new CmsBlockKeyNotCreatedException('Cannot create key: maximum iterations threshold met.');
            }

            $candidate = $this->suggestCandidate($index);
            $index++;
            $attempt++;
        } while (!$this->isCandidateSuitable($candidate));

        return $candidate;
    }

    /**
     * @param int $index
     *
     * @return string
     */
    protected function suggestCandidate(int $index): string
    {
        return sprintf('%s-%d', static::KEY_GENERATOR_PREFIX, $index);
    }

    /**
     * @param string $candidate
     *
     * @return bool
     */
    protected function isCandidateSuitable(string $candidate): bool
    {
        return !$this->cmsBlockRepository->hasKey($candidate);
    }
}
