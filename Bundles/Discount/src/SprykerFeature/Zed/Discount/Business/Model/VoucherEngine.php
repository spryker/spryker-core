<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\VoucherCreateInterface;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\Discount\DiscountConfigInterface;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;
use Symfony\Component\HttpFoundation\Request;

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
     * @param VoucherCreateInterface $voucherCreateTransfer
     */
    public function createVoucherCodes(VoucherCreateInterface $voucherCreateTransfer)
    {
        $nextVoucherBatchValue = 0;
        $codeCollisions = 0;
        $voucherPoolEntity = $this->queryContainer
            ->queryVoucherPool()
            ->findPk($voucherCreateTransfer->getIdVoucherPool());

        $highestBatchValueOnVouchers = $this->queryContainer
            ->queryDiscountVoucher()
            ->filterByFkDiscountVoucherPool($voucherCreateTransfer->getIdVoucherPool())
            ->orderByVoucherBatch(Criteria::DESC)
            ->findOne()
        ;

        if (null !== $highestBatchValueOnVouchers && $voucherCreateTransfer->getQuantity() > 1) {
            $nextVoucherBatchValue = $highestBatchValueOnVouchers->getVoucherBatch() + 1;
        }

        $voucherCreateTransfer->setVoucherBatch($nextVoucherBatchValue);

        $length = $this->settings->getVoucherCodeLength();

        for ($i = 0; $i < $voucherCreateTransfer->getQuantity(); $i++) {
            try {
                $code = $this->getRandomVoucherCode($length);

                if ($voucherCreateTransfer->getIncludeTemplate()) {
                    $code = $this->getCodeWithTemplate($voucherPoolEntity, $code);
                }

                $voucherCreateTransfer->setCode($code);

                $this->createVoucherCode($voucherCreateTransfer);
            } catch (\Exception $e) {
                $codeCollisions++;
            }
        }

        if ($codeCollisions > 0) {
            $voucherCreateTransfer->getQuantity($codeCollisions);
            $this->createVoucherCodes($voucherCreateTransfer);
        }
    }


    /**
     * @param VoucherCreateInterface $voucherCreateTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return SpyDiscountVoucher
     */
    public function createVoucherCode(VoucherCreateInterface $voucherCreateTransfer)
    {
        $voucherEntity = new SpyDiscountVoucher();
        $voucherEntity->fromArray($voucherCreateTransfer->toArray());

        $voucherEntity
            ->setFkDiscountVoucherPool($voucherCreateTransfer->getIdVoucherPool())
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
        $specialCharacters = $allowedCharacters[DiscountConfigInterface::KEY_VOUCHER_CODE_SPECIAL_CHARACTERS];

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

            if (count($specialCharacters)) {
                $code .= $specialCharacters[rand(0, count($specialCharacters) - 1)];
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
            return $code;
        }

        return str_replace($this->settings->getVoucherPoolTemplateReplacementString(), $code, $template);
    }

}
