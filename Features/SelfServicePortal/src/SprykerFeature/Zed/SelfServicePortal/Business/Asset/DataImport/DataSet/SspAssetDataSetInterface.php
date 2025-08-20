<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\DataSet;

interface SspAssetDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_REFERENCE = 'reference';

    /**
     * @var string
     */
    public const COLUMN_NAME = 'name';

    /**
     * @var string
     */
    public const COLUMN_SERIAL_NUMBER = 'serial_number';

    /**
     * @var string
     */
    public const COLUMN_STATUS = 'status';

    /**
     * @var string
     */
    public const COLUMN_NOTE = 'note';

    /**
     * @var string
     */
    public const COLUMN_EXTERNAL_IMAGE_URL = 'external_image_url';

    /**
     * @var string
     */
    public const ID_SSP_ASSET = 'id_ssp_asset';

    /**
     * @var string
     */
    public const BUSINESS_UNIT_KEY = 'business_unit_key';

    /**
     * @var string
     */
    public const FK_COMPANY_BUSINESS_UNIT = 'fk_company_business_unit';

    /**
     * @var string
     */
    public const ASSIGNED_BUSINESS_UNIT_KEYS = 'assigned_business_unit_keys';

    /**
     * @var string
     */
    public const ASSIGNED_BUSINESS_UNIT_IDS = 'assigned_business_unit_ids';
}
