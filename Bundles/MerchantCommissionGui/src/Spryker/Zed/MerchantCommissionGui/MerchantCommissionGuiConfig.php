<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantCommissionGuiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const KEY_MERCHANT_COMMISSION_KEY = 'key';

    /**
     * @var string
     */
    protected const KEY_NAME = 'name';

    /**
     * @var string
     */
    protected const KEY_DESCRIPTION = 'description';

    /**
     * @var string
     */
    protected const KEY_VALID_FROM = 'valid_from';

    /**
     * @var string
     */
    protected const KEY_VALID_TO = 'valid_to';

    /**
     * @var string
     */
    protected const KEY_IS_ACTIVE = 'is_active';

    /**
     * @var string
     */
    protected const KEY_AMOUNT = 'amount';

    /**
     * @var string
     */
    protected const KEY_CALCULATOR_TYPE_PLUGIN = 'calculator_type_plugin';

    /**
     * @var string
     */
    protected const KEY_GROUP = 'group';

    /**
     * @var string
     */
    protected const KEY_PRIORITY = 'priority';

    /**
     * @var string
     */
    protected const KEY_ITEM_CONDITION = 'item_condition';

    /**
     * @var string
     */
    protected const KEY_ORDER_CONDITION = 'order_condition';

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
    protected const FILE_MAX_SIZE = '50M';

    /**
     * @var array<string, list<string>>
     */
    protected const FILE_ALLOWED_EXTENSIONS_WITH_MIME_TYPES = [
        'csv' => ['text/csv', 'text/plain'],
    ];

    /**
     * @var string
     */
    protected const MERCHANT_COMMISSIONS_EXPORT_FILE_NAME = 'merchant_commissions_%s.csv';

    /**
     * @api
     *
     * @return string
     */
    public function getMaxFileSize(): string
    {
        return static::FILE_MAX_SIZE;
    }

    /**
     * Specification:
     * - Specifies allowed extensions with their MIME types for the uploaded file.
     *
     * @api
     *
     * @return array<string, list<string>>
     */
    public function getFileAllowedExtensionsWithMimeTypes(): array
    {
        return static::FILE_ALLOWED_EXTENSIONS_WITH_MIME_TYPES;
    }

    /**
     * Specification:
     * - List of columns required in csv file.
     *
     * @api
     *
     * @return list<string>
     */
    public function getCsvFileRequiredColumnsList(): array
    {
        return [
            static::KEY_MERCHANT_COMMISSION_KEY,
            static::KEY_NAME,
            static::KEY_DESCRIPTION,
            static::KEY_VALID_FROM,
            static::KEY_VALID_TO,
            static::KEY_IS_ACTIVE,
            static::KEY_AMOUNT,
            static::KEY_CALCULATOR_TYPE_PLUGIN,
            static::KEY_GROUP,
            static::KEY_PRIORITY,
            static::KEY_ITEM_CONDITION,
            static::KEY_ORDER_CONDITION,
            static::KEY_STORES,
            static::KEY_MERCHANTS_ALLOW_LIST,
            static::KEY_FIXED_AMOUNT_CONFIGURATION,
        ];
    }

    /**
     * Specification:
     * - Specifies if merchant commission data import should be transactional.
     *
     * @api
     *
     * @return bool
     */
    public function isTransactionalDataImport(): bool
    {
        return true;
    }

    /**
     * Specification:
     * - Returns a file name for merchant commissions export.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantCommissionsExportFileName(): string
    {
        return sprintf(static::MERCHANT_COMMISSIONS_EXPORT_FILE_NAME, date('Y-m-d_H-i-s'));
    }
}
