<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Updater;

use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantRelationshipValidationErrorCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationship\Business\KeyGenerator\MerchantRelationshipKeyGeneratorInterface;
use Spryker\Zed\MerchantRelationship\Business\Validator\MerchantRelationshipValidatorInterface;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface;

class MerchantRelationshipUpdater implements MerchantRelationshipUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface
     */
    protected $merchantRelationshipEntityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Validator\MerchantRelationshipValidatorInterface
     */
    protected $merchantRelationshipValidator;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\KeyGenerator\MerchantRelationshipKeyGeneratorInterface
     */
    protected $merchantRelationshipKeyGenerator;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\Updater\MerchantRelationshipCompanyBusinessUnitUpdaterInterface
     */
    protected $merchantRelationshipCompanyBusinessUnitUpdater;

    /**
     * @var array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostUpdatePluginInterface>
     */
    protected $merchantRelationshipPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface $merchantRelationshipEntityManager
     * @param \Spryker\Zed\MerchantRelationship\Business\Validator\MerchantRelationshipValidatorInterface $merchantRelationshipUpdateValidator
     * @param \Spryker\Zed\MerchantRelationship\Business\KeyGenerator\MerchantRelationshipKeyGeneratorInterface $merchantRelationshipKeyGenerator
     * @param \Spryker\Zed\MerchantRelationship\Business\Updater\MerchantRelationshipCompanyBusinessUnitUpdaterInterface $merchantRelationshipCompanyBusinessUnitUpdater
     * @param array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostUpdatePluginInterface> $merchantRelationshipPostUpdatePlugins
     */
    public function __construct(
        MerchantRelationshipEntityManagerInterface $merchantRelationshipEntityManager,
        MerchantRelationshipValidatorInterface $merchantRelationshipUpdateValidator,
        MerchantRelationshipKeyGeneratorInterface $merchantRelationshipKeyGenerator,
        MerchantRelationshipCompanyBusinessUnitUpdaterInterface $merchantRelationshipCompanyBusinessUnitUpdater,
        array $merchantRelationshipPostUpdatePlugins
    ) {
        $this->merchantRelationshipEntityManager = $merchantRelationshipEntityManager;
        $this->merchantRelationshipValidator = $merchantRelationshipUpdateValidator;
        $this->merchantRelationshipKeyGenerator = $merchantRelationshipKeyGenerator;
        $this->merchantRelationshipCompanyBusinessUnitUpdater = $merchantRelationshipCompanyBusinessUnitUpdater;
        $this->merchantRelationshipPostUpdatePlugins = $merchantRelationshipPostUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer|null $merchantRelationshipRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|\Generated\Shared\Transfer\MerchantRelationshipResponseTransfer
     */
    public function update(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        ?MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer = null
    ) {
        if ($merchantRelationshipRequestTransfer) {
            return $this->updateByMerchantRelationshipRequestTransfer($merchantRelationshipRequestTransfer);
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($merchantRelationshipTransfer) {
            return $this->executeUpdateTransaction($merchantRelationshipTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipResponseTransfer
     */
    protected function updateByMerchantRelationshipRequestTransfer(
        MerchantRelationshipRequestTransfer $merchantRelationshipRequestTransfer
    ): MerchantRelationshipResponseTransfer {
        $merchantRelationshipTransfer = $merchantRelationshipRequestTransfer->getMerchantRelationshipOrFail();

        $merchantRelationshipResponseTransfer = (new MerchantRelationshipResponseTransfer())
            ->setIsSuccessful(true)
            ->setMerchantRelationship($merchantRelationshipTransfer);

        $merchantRelationshipValidationErrorCollectionTransfer = $this->merchantRelationshipValidator->validate(
            $merchantRelationshipTransfer,
            new MerchantRelationshipValidationErrorCollectionTransfer(),
        );

        if ($merchantRelationshipValidationErrorCollectionTransfer->getErrors()->count()) {
            $merchantRelationshipResponseTransfer->setIsSuccessful(false);

            return $merchantRelationshipResponseTransfer->setErrors($merchantRelationshipValidationErrorCollectionTransfer->getErrors());
        }

        $merchantRelationshipTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantRelationshipTransfer) {
            return $this->executeUpdateTransaction($merchantRelationshipTransfer);
        });

        return $merchantRelationshipResponseTransfer->setMerchantRelationship($merchantRelationshipTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function executeUpdateTransaction(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationshipTransfer->requireIdMerchantRelationship()
            ->requireFkMerchant()
            ->requireFkCompanyBusinessUnit();

        if (!$merchantRelationshipTransfer->getMerchantRelationshipKey()) {
            $merchantRelationshipTransfer->setMerchantRelationshipKey(
                $this->merchantRelationshipKeyGenerator->generateMerchantRelationshipKey(),
            );
        }

        $merchantRelationshipTransfer = $this->merchantRelationshipEntityManager->saveMerchantRelationship($merchantRelationshipTransfer);
        $merchantRelationshipTransfer = $this->merchantRelationshipCompanyBusinessUnitUpdater->updateMerchantRelationshipCompanyBusinessUnitRelations($merchantRelationshipTransfer);
        $merchantRelationshipTransfer = $this->executeMerchantRelationshipPostUpdatePlugins($merchantRelationshipTransfer);

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function executeMerchantRelationshipPostUpdatePlugins(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        foreach ($this->merchantRelationshipPostUpdatePlugins as $merchantRelationshipPostUpdatePlugin) {
            $merchantRelationshipTransfer = $merchantRelationshipPostUpdatePlugin->execute($merchantRelationshipTransfer);
        }

        return $merchantRelationshipTransfer;
    }
}
