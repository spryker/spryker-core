<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\MerchantRelationshipThreshold;

use Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueGlossaryKeyGeneratorInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueTranslationWriterInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueEntityManagerInterface;

class MerchantRelationshipThresholdWriter implements MerchantRelationshipThresholdWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface
     */
    protected $minimumOrderValueFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueEntityManagerInterface
     */
    protected $merchantRelationshipMinimumOrderValueEntityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueGlossaryKeyGeneratorInterface
     */
    protected $glossaryKeyGenerator;

    /**
     * @var \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueTranslationWriterInterface
     */
    protected $translationWriter;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Dependency\Facade\MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface $minimumOrderValueFacade
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\MerchantRelationshipMinimumOrderValueEntityManagerInterface $merchantRelationshipMinimumOrderValueEntityManager
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueGlossaryKeyGeneratorInterface $glossaryKeyGenerator
     * @param \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Business\Translation\MerchantRelationshipMinimumOrderValueTranslationWriterInterface $translationWriter
     */
    public function __construct(
        MerchantRelationshipMinimumOrderValueToMinimumOrderValueFacadeInterface $minimumOrderValueFacade,
        MerchantRelationshipMinimumOrderValueEntityManagerInterface $merchantRelationshipMinimumOrderValueEntityManager,
        MerchantRelationshipMinimumOrderValueGlossaryKeyGeneratorInterface $glossaryKeyGenerator,
        MerchantRelationshipMinimumOrderValueTranslationWriterInterface $translationWriter
    ) {
        $this->minimumOrderValueFacade = $minimumOrderValueFacade;
        $this->merchantRelationshipMinimumOrderValueEntityManager = $merchantRelationshipMinimumOrderValueEntityManager;
        $this->glossaryKeyGenerator = $glossaryKeyGenerator;
        $this->translationWriter = $translationWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    public function setMerchantRelationshipThreshold(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $this->minimumOrderValueFacade->isStrategyValid(
            $merchantRelationshipMinimumOrderValueTransfer->getMinimumOrderValue()
        );

        $this->hydrateMinimumOrderValueType($merchantRelationshipMinimumOrderValueTransfer);

        $this->glossaryKeyGenerator->assignMessageGlossaryKey($merchantRelationshipMinimumOrderValueTransfer);
        $this->merchantRelationshipMinimumOrderValueEntityManager
            ->setMerchantRelationshipThreshold($merchantRelationshipMinimumOrderValueTransfer);

        $this->translationWriter->saveLocalizedMessages($merchantRelationshipMinimumOrderValueTransfer);

        return $merchantRelationshipMinimumOrderValueTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipMinimumOrderValueTransfer
     */
    protected function hydrateMinimumOrderValueType(
        MerchantRelationshipMinimumOrderValueTransfer $merchantRelationshipMinimumOrderValueTransfer
    ): MerchantRelationshipMinimumOrderValueTransfer {
        $minimumOrderValueTypeTransfer = $this->minimumOrderValueFacade
            ->getMinimumOrderValueTypeByKey(
                $merchantRelationshipMinimumOrderValueTransfer
                    ->getMinimumOrderValue()
                    ->getMinimumOrderValueType()
            );

        $merchantRelationshipMinimumOrderValueTransfer
            ->getMinimumOrderValue()
            ->setMinimumOrderValueType($minimumOrderValueTypeTransfer);

        return $merchantRelationshipMinimumOrderValueTransfer;
    }
}
