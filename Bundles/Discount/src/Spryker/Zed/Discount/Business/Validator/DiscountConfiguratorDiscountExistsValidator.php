<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Validator;

use Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer;
use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToTranslatorFacadeInterface;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;

class DiscountConfiguratorDiscountExistsValidator implements DiscountConfiguratorValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_DISCOUNT_DOES_NOT_EXIST = 'Discount with id %s doesn\'t exist';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_ID_DISCOUNT = '%s';

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface
     */
    protected $discountRepository;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface $discountRepository
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        DiscountRepositoryInterface $discountRepository,
        DiscountToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->discountRepository = $discountRepository;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     * @param \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer $discountConfiguratorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorResponseTransfer
     */
    public function validateDiscountConfigurator(
        DiscountConfiguratorTransfer $discountConfiguratorTransfer,
        DiscountConfiguratorResponseTransfer $discountConfiguratorResponseTransfer
    ): DiscountConfiguratorResponseTransfer {
        $idDiscount = $discountConfiguratorTransfer->getDiscountGeneralOrFail()->getIdDiscountOrFail();
        if ($this->discountRepository->discountExists($idDiscount)) {
            return $discountConfiguratorResponseTransfer;
        }

        $messageTransfer = $this->createMessageTransfer(
            $this->translatorFacade->trans(static::ERROR_MESSAGE_DISCOUNT_DOES_NOT_EXIST),
            [static::ERROR_MESSAGE_PARAMETER_ID_DISCOUNT => $idDiscount],
        );

        return $discountConfiguratorResponseTransfer
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }

    /**
     * @param string $value
     * @param array<string, mixed> $parameters
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $value, array $parameters): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($value)
            ->setParameters($parameters);
    }
}
