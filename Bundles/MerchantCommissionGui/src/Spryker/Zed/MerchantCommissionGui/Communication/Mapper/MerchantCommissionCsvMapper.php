<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MerchantCommissionGui\Communication\Transformer\MerchantCommissionAmountTransformerInterface;

class MerchantCommissionCsvMapper implements MerchantCommissionCsvMapperInterface
{
    /**
     * @var string
     */
    protected const KEY_GROUP = 'group';

    /**
     * @var string
     */
    protected const KEY_STORES = 'stores';

    /**
     * @var string
     */
    protected const KEY_MERCHANTS_ALLOW_LIST = 'merchants_allow_list';

    /**
     * @var string
     */
    protected const KEY_FIXED_AMOUNT_CONFIGURATION = 'fixed_amount_configuration';

    /**
     * @var string
     */
    protected const KEY_AMOUNT = 'amount';

    /**
     * @var string
     */
    protected const KEY_CALCULATOR_TYPE_PLUGIN = 'calculator_type_plugin';

    /**
     * @var int
     */
    protected const INDEX_CURRENCY_CODE = 0;

    /**
     * @var int
     */
    protected const INDEX_NET_AMOUNT = 1;

    /**
     * @var int
     */
    protected const INDEX_GROSS_AMOUNT = 2;

    /**
     * @var \Spryker\Zed\MerchantCommissionGui\Communication\Transformer\MerchantCommissionAmountTransformerInterface
     */
    protected MerchantCommissionAmountTransformerInterface $merchantCommissionAmountTransformer;

    /**
     * @param \Spryker\Zed\MerchantCommissionGui\Communication\Transformer\MerchantCommissionAmountTransformerInterface $merchantCommissionAmountTransformer
     */
    public function __construct(MerchantCommissionAmountTransformerInterface $merchantCommissionAmountTransformer)
    {
        $this->merchantCommissionAmountTransformer = $merchantCommissionAmountTransformer;
    }

    /**
     * @param array<string, mixed> $merchantCommissionData
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function mapMerchantCommissionRowDataToMerchantCommissionTransfer(
        array $merchantCommissionData,
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer {
        if (isset($merchantCommissionData[static::KEY_AMOUNT])) {
            $merchantCommissionData[static::KEY_AMOUNT] = $this->merchantCommissionAmountTransformer->transformMerchantCommissionAmount(
                $merchantCommissionData[static::KEY_CALCULATOR_TYPE_PLUGIN],
                (float)$merchantCommissionData[static::KEY_AMOUNT],
            );
        }

        $merchantCommissionTransfer->fromArray($merchantCommissionData, true);
        $merchantCommissionTransfer->setMerchantCommissionGroup($this->mapMerchantCommissionGroupDataToMerchantCommissionGroupTransfer(
            $merchantCommissionData,
            new MerchantCommissionGroupTransfer(),
        ));
        $merchantCommissionTransfer->setStoreRelation($this->mapStoreRelationDataToStoreRelationTransfer(
            $merchantCommissionData,
            new StoreRelationTransfer(),
        ));
        $merchantCommissionTransfer->setMerchants($this->mapMerchantAllowListToMerchantTransfers(
            $merchantCommissionData,
            new ArrayObject(),
        ));
        $merchantCommissionTransfer->setMerchantCommissionAmounts($this->mapMerchantCommissionAmountDataToMerchantCommissionAmountTransfers(
            $merchantCommissionData,
            new ArrayObject(),
        ));

        return $merchantCommissionTransfer;
    }

    /**
     * @param array<string, mixed> $merchantCommissionData
     * @param \Generated\Shared\Transfer\MerchantCommissionGroupTransfer $merchantCommissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionGroupTransfer
     */
    protected function mapMerchantCommissionGroupDataToMerchantCommissionGroupTransfer(
        array $merchantCommissionData,
        MerchantCommissionGroupTransfer $merchantCommissionGroupTransfer
    ): MerchantCommissionGroupTransfer {
        return $merchantCommissionGroupTransfer->setKey($merchantCommissionData[static::KEY_GROUP]);
    }

    /**
     * @param array<string, mixed> $merchantCommissionData
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function mapStoreRelationDataToStoreRelationTransfer(
        array $merchantCommissionData,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        $storeNames = array_map('trim', explode(',', $merchantCommissionData[static::KEY_STORES]));
        $storeNames = array_filter($storeNames);

        foreach ($storeNames as $storeName) {
            $storeRelationTransfer->addStores((new StoreTransfer())->setName($storeName));
        }

        return $storeRelationTransfer;
    }

    /**
     * @param array<string, mixed> $merchantCommissionData
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantTransfer>
     */
    protected function mapMerchantAllowListToMerchantTransfers(
        array $merchantCommissionData,
        ArrayObject $merchantTransfers
    ): ArrayObject {
        $merchantReferences = array_map('trim', explode(',', $merchantCommissionData[static::KEY_MERCHANTS_ALLOW_LIST]));
        $merchantReferences = array_filter($merchantReferences);

        foreach ($merchantReferences as $merchantReference) {
            $merchantTransfers->append((new MerchantTransfer())->setMerchantReference($merchantReference));
        }

        return $merchantTransfers;
    }

    /**
     * @param array<string, mixed> $merchantCommissionData
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer> $merchantCommissionAmountTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer>
     */
    protected function mapMerchantCommissionAmountDataToMerchantCommissionAmountTransfers(
        array $merchantCommissionData,
        ArrayObject $merchantCommissionAmountTransfers
    ): ArrayObject {
        if (!$merchantCommissionData[static::KEY_FIXED_AMOUNT_CONFIGURATION]) {
            return $merchantCommissionAmountTransfers;
        }

        $merchantCommissionAmountData = array_map('trim', explode(',', $merchantCommissionData[static::KEY_FIXED_AMOUNT_CONFIGURATION]));
        $merchantCommissionAmountData = array_filter($merchantCommissionAmountData);

        foreach ($merchantCommissionAmountData as $merchantCommissionAmount) {
            $merchantCommissionAmountParts = array_map('trim', explode('|', $merchantCommissionAmount));
            $grossAmount = null;
            if (isset($merchantCommissionAmountParts[static::INDEX_GROSS_AMOUNT])) {
                $grossAmount = $this->merchantCommissionAmountTransformer->transformMerchantCommissionAmount(
                    $merchantCommissionData[static::KEY_CALCULATOR_TYPE_PLUGIN],
                    (float)$merchantCommissionAmountParts[static::INDEX_GROSS_AMOUNT],
                );
            }

            $netAmount = null;
            if (isset($merchantCommissionAmountParts[static::INDEX_NET_AMOUNT])) {
                $netAmount = $this->merchantCommissionAmountTransformer->transformMerchantCommissionAmount(
                    $merchantCommissionData[static::KEY_CALCULATOR_TYPE_PLUGIN],
                    (float)$merchantCommissionAmountParts[static::INDEX_NET_AMOUNT],
                );
            }

            $merchantCommissionAmountTransfer = (new MerchantCommissionAmountTransfer())
                ->setCurrency((new CurrencyTransfer())->setCode((string)$merchantCommissionAmountParts[static::INDEX_CURRENCY_CODE]))
                ->setNetAmount($netAmount)
                ->setGrossAmount($grossAmount);
            $merchantCommissionAmountTransfers->append($merchantCommissionAmountTransfer);
        }

        return $merchantCommissionAmountTransfers;
    }
}
