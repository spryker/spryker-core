<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Business\Key;

use Spryker\Zed\Glossary\Persistence\GlossaryRepositoryInterface;

class KeyReader implements KeyReaderInterface
{
    /**
     * @var \Spryker\Zed\Glossary\Persistence\GlossaryRepositoryInterface
     */
    protected $glossaryRepository;

    /**
     * @param \Spryker\Zed\Glossary\Persistence\GlossaryRepositoryInterface $glossaryRepository
     */
    public function __construct(GlossaryRepositoryInterface $glossaryRepository)
    {
        $this->glossaryRepository = $glossaryRepository;
    }

    /**
     * @param string[] $glossaryKeys
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer[]
     */
    public function getGlossaryKeyTransfersByGlossaryKeys(array $glossaryKeys): array
    {
        return $this->glossaryRepository->getGlossaryKeyTransfersByGlossaryKeys($glossaryKeys);
    }
}
