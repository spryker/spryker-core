<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\VoucherInterface;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\Discount\DiscountConfigInterface;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;

/**
 * Class VoucherEngine
 */
class VoucherEngine
{

    /**
     * @var DiscountConfigInterface
     */
    protected $settings;

    /**
     * @var DiscountQueryContainer
     */
    protected $queryContainer;

    /**
     * @param DiscountConfigInterface $settings
     * @param DiscountQueryContainer $queryContainer
     */
    public function __construct(DiscountConfigInterface $settings, DiscountQueryContainer $queryContainer)
    {
        $this->settings = $settings;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param VoucherInterface $voucherTransfer
     */
    public function createVoucherCodes(VoucherInterface $voucherTransfer)
    {
        $codeCollisions = 0;
        $voucherPoolEntity = $this->queryContainer
            ->queryVoucherPool()
            ->findPk($voucherTransfer->getFkDiscountVoucherPool());

        $nextVoucherBatchValue = $this->getNextBatchValueForVouchers($voucherTransfer);

        $voucherTransfer->setVoucherBatch($nextVoucherBatchValue);

        $voucherTransfer->setIncludeTemplate(true);
        $length = $voucherTransfer->getCodeLength();
        $voucherPoolEntity->setTemplate($voucherTransfer->getCustomCode());

        for ($i = 0; $i < $voucherTransfer->getQuantity(); $i++) {
            try {
                $code = $this->getRandomVoucherCode($length);

                if ($voucherTransfer->getIncludeTemplate()) {
                    $code = $this->getCodeWithTemplate($voucherPoolEntity, $code);
                }

                $voucherTransfer->setCode($code);

                $this->createVoucherCode($voucherTransfer);
            } catch (\Exception $e) {
                $codeCollisions++;
            }
        }

        if ($codeCollisions > 0) {
            $voucherTransfer->getQuantity($codeCollisions);
            $this->createVoucherCodes($voucherTransfer);
        }
    }


    /**
     * @param VoucherInterface $voucherTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return SpyDiscountVoucher
     */
    public function createVoucherCode(VoucherInterface $voucherTransfer)
    {
        $voucherEntity = new SpyDiscountVoucher();
        $voucherEntity->fromArray($voucherTransfer->toArray());

        $voucherEntity
            ->setFkDiscountVoucherPool($voucherTransfer->getFkDiscountVoucherPool())
            ->setIsActive(true)
            ->save();

        return $voucherEntity;
    }

    /**
     * @param int $length
     * @param bool $asMd5
     *
     * @return string
     */
    protected function getRandomVoucherCode($length, $asMd5 = false)
    {
        $allowedCharacters = $this->settings->getVoucherCodeCharacters();
        srand((double) microtime() * 1000000);

        $consonants = $allowedCharacters[DiscountConfigInterface::KEY_VOUCHER_CODE_CONSONANTS];
        $vowels = $allowedCharacters[DiscountConfigInterface::KEY_VOUCHER_CODE_VOWELS];
        $numbers = $allowedCharacters[DiscountConfigInterface::KEY_VOUCHER_CODE_NUMBERS];

        $code = '';

        while (strlen($code) < $length) {
            if (count($consonants)) {
                $code .= $consonants[rand(0, count($consonants) - 1)];
            }

            if (count($vowels)) {
                $code .= $vowels[rand(0, count($vowels) - 1)];
            }

            if (count($numbers)) {
                $code .= $numbers[rand(0, count($numbers) - 1)];
            }
        }

        if ($asMd5) {
            return substr(md5($code), 0, $length);
        }

        return substr($code, 0, $length);
    }

    /**
     * @param SpyDiscountVoucherPool $voucherPoolEntity
     * @param string $code
     *
     * @return string
     */
    protected function getCodeWithTemplate(SpyDiscountVoucherPool $voucherPoolEntity, $code)
    {
        $template = $voucherPoolEntity->getTemplate();
        $replacementString = $this->settings->getVoucherPoolTemplateReplacementString();

        if (!$template) {
            return $code;
        }

        if (!strstr($template, $replacementString)) {
            return $voucherPoolEntity->getTemplate() . $code;
        }

        return str_replace($this->settings->getVoucherPoolTemplateReplacementString(), $code, $template);
    }

    /**
     * @param VoucherInterface $voucherTransfer
     *
     * @return int
     */
    protected function getNextBatchValueForVouchers(VoucherInterface $voucherTransfer)
    {
        $nextVoucherBatchValue = 0;

        if ($voucherTransfer->getQuantity() > 1) {
            $highestBatchValueOnVouchers = $this->queryContainer
                ->queryDiscountVoucher()
                ->filterByFkDiscountVoucherPool($voucherTransfer->getFkDiscountVoucherPool())
                ->orderByVoucherBatch(Criteria::DESC)
                ->findOne()
            ;

            if (null === $highestBatchValueOnVouchers) {

                return 1;
            }

            return $highestBatchValueOnVouchers->getVoucherBatch() + 1;
        }

        return $nextVoucherBatchValue;
    }

}
