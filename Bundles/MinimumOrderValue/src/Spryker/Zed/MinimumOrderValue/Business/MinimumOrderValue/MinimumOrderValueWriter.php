<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValue;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueThresholdInvalidArgumentException;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface;
use Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueGlossaryKeyGeneratorInterface;
use Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationWriterInterface;
use Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface;

class MinimumOrderValueWriter implements MinimumOrderValueWriterInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface
     */
    protected $minimumOrderValueStrategyResolver;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface
     */
    protected $minimumOrderValueEntityManager;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueGlossaryKeyGeneratorInterface
     */
    protected $glossaryKeyGenerator;

    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationWriterInterface
     */
    protected $translationWriter;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategy\Resolver\MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver
     * @param \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueEntityManagerInterface $minimumOrderValueEntityManager
     * @param \Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueGlossaryKeyGeneratorInterface $glossaryKeyGenerator
     * @param \Spryker\Zed\MinimumOrderValue\Business\Translation\MinimumOrderValueTranslationWriterInterface $translationWriter
     */
    public function __construct(
        MinimumOrderValueStrategyResolverInterface $minimumOrderValueStrategyResolver,
        MinimumOrderValueEntityManagerInterface $minimumOrderValueEntityManager,
        MinimumOrderValueGlossaryKeyGeneratorInterface $glossaryKeyGenerator,
        MinimumOrderValueTranslationWriterInterface $translationWriter
    ) {
        $this->minimumOrderValueStrategyResolver = $minimumOrderValueStrategyResolver;
        $this->minimumOrderValueEntityManager = $minimumOrderValueEntityManager;
        $this->glossaryKeyGenerator = $glossaryKeyGenerator;
        $this->translationWriter = $translationWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueThresholdInvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function saveMinimumOrderValue(
        MinimumOrderValueTransfer $minimumOrderValueTransfer
    ): MinimumOrderValueTransfer {
        $minimumOrderValueTransfer->requireMinimumOrderValueThreshold();

        $minimumOrderValueTransfer
            ->getMinimumOrderValueThreshold()
            ->getMinimumOrderValueType()
            ->requireKey();

        $minimumOrderValueStrategy = $this->minimumOrderValueStrategyResolver
            ->resolveMinimumOrderValueStrategy(
                $minimumOrderValueTransfer->getMinimumOrderValueThreshold()->getMinimumOrderValueType()->getKey()
            );

        if (!$minimumOrderValueStrategy->isValid($minimumOrderValueTransfer->getMinimumOrderValueThreshold())) {
            throw new MinimumOrderValueThresholdInvalidArgumentException();
        }

        if (!$minimumOrderValueTransfer->getMinimumOrderValueThreshold()
            ->getMinimumOrderValueType()
            ->getIdMinimumOrderValueType()
        ) {
            $minimumOrderValueTypeTransfer = $this->minimumOrderValueEntityManager
                ->saveMinimumOrderValueType($minimumOrderValueStrategy->toTransfer());

            $minimumOrderValueTransfer->getMinimumOrderValueThreshold()
                ->setMinimumOrderValueType(
                    $minimumOrderValueTypeTransfer
                );
        }

        $this->glossaryKeyGenerator->assignMessageGlossaryKey($minimumOrderValueTransfer);
        $this->minimumOrderValueEntityManager->saveMinimumOrderValue($minimumOrderValueTransfer);

        $this->translationWriter->saveLocalizedMessages($minimumOrderValueTransfer);

        return $minimumOrderValueTransfer;
    }
}
