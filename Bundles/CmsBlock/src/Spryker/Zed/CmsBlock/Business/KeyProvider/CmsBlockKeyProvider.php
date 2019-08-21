<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\KeyProvider;

use Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface;

class CmsBlockKeyProvider implements CmsBlockKeyProviderInterface
{
    protected const KEY_GENERATOR_PREFIX = 'blck';

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
     * @param int|null $idCmsBlock
     *
     * @return string
     */
    public function generateKey(?int $idCmsBlock = null): string
    {
        if ($idCmsBlock) {
            $keyCandidate = $this->suggestCandidate($idCmsBlock);

            if (!$this->isCandidateNotSuitable($keyCandidate)) {
                return $keyCandidate;
            }
        }

        $index = $this->cmsBlockRepository->findMaxIdCmsBlock() + 1;

        return $this->suggestCandidate($index);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return string
     */
    public function getKeyByIdCmsBlock(int $idCmsBlock): string
    {
        $cmsBlockTransfer = $this->cmsBlockRepository->findCmsBlockById($idCmsBlock);

        if (!$cmsBlockTransfer || !$cmsBlockTransfer->getKey()) {
            return $this->generateKey($idCmsBlock);
        }

        return $cmsBlockTransfer->getKey();
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
    protected function isCandidateNotSuitable(string $candidate): bool
    {
        return $this->cmsBlockRepository->hasKey($candidate);
    }
}
