<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Voucher;

use Generated\Shared\Transfer\MessageTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class VoucherValidator implements VoucherValidatorInterface
{
    public const REASON_VOUCHER_CODE_NOT_ACTIVE = 'discount.voucher_code.not_active';
    public const REASON_VOUCHER_CODE_POOL_MISSING = 'discount.voucher_code.pool_not_set';
    public const REASON_VOUCHER_CODE_POOL_NOT_ACTIVE = 'discount.voucher_code.pool_not_active';
    public const REASON_VOUCHER_CODE_LIMIT_REACHED = 'discount.voucher_code.usage_limit.reached';
    public const REASON_VOUCHER_CODE_NOT_FOUND = 'discount.voucher_code.code_not_found';

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface $messengerFacade
     */
    public function __construct(
        DiscountQueryContainerInterface $discountQueryContainer,
        DiscountToMessengerInterface $messengerFacade
    ) {

        $this->discountQueryContainer = $discountQueryContainer;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function isUsable($code)
    {
        $discountVoucherEntity = $this->discountQueryContainer
            ->queryVoucher($code)
            ->findOne();

        if (!$discountVoucherEntity) {
            $this->addErrorMessage(static::REASON_VOUCHER_CODE_NOT_FOUND);

            return false;
        }

        return $this->validateDiscountVoucher($discountVoucherEntity);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucher $discountVoucherEntity
     *
     * @return bool
     */
    protected function validateDiscountVoucher(SpyDiscountVoucher $discountVoucherEntity)
    {
        if (!$discountVoucherEntity->getIsActive()) {
            $this->addErrorMessage(static::REASON_VOUCHER_CODE_NOT_ACTIVE);

            return false;
        }

        /** @var \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool|null $voucherPoolEntity */
        $voucherPoolEntity = $discountVoucherEntity->getVoucherPool();
        if (!$voucherPoolEntity) {
            $this->addErrorMessage(static::REASON_VOUCHER_CODE_POOL_MISSING);

            return false;
        }

        if (!$voucherPoolEntity->getIsActive()) {
            $this->addErrorMessage(static::REASON_VOUCHER_CODE_POOL_NOT_ACTIVE);

            return false;
        }

        if (!$this->isValidNumberOfUses($discountVoucherEntity)) {
            $this->addErrorMessage(
                static::REASON_VOUCHER_CODE_LIMIT_REACHED,
                ['%code%' => $discountVoucherEntity->getCode()]
            );

            return false;
        }

        return true;
    }

    /**
     * @deprecated Use addErrorMessage()
     *
     * @param string $message
     *
     * @return void
     */
    protected function addMessage($message)
    {
        $this->addErrorMessage($message);
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return void
     */
    protected function addErrorMessage(string $message, array $data = []): void
    {
        $messageTransfer = $this->createMessageTransfer($message, $data);

        $this->messengerFacade->addErrorMessage($messageTransfer);
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return void
     */
    protected function addInfoMessage(string $message, array $data = []): void
    {
        $messageTransfer = $this->createMessageTransfer($message, $data);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $message, array $data): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);
        $messageTransfer->setParameters($data);

        return $messageTransfer;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucher $discountVoucherEntity
     *
     * @return bool
     */
    protected function isValidNumberOfUses(SpyDiscountVoucher $discountVoucherEntity)
    {
        if ($discountVoucherEntity->getMaxNumberOfUses() > 0 &&
            $discountVoucherEntity->getNumberOfUses() >= $discountVoucherEntity->getMaxNumberOfUses()) {
            return false;
        }

        return true;
    }
}
