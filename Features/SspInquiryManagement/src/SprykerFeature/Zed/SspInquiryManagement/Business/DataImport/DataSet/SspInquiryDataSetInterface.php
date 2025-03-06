<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\DataImport\DataSet;

interface SspInquiryDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_REFERENCE = 'reference';

    /**
     * @var string
     */
    public const COLUMN_SUBJECT = 'subject';

    /**
     * @var string
     */
    public const COLUMN_DESCRIPTION = 'description';

    /**
     * @var string
     */
    public const KEY_COMPANY_USER = 'company_user_key';

    /**
     * @var string
     */
    public const TYPE = 'type';

    /**
     * @var string
     */
    public const FK_COMPANY_USER = 'fk_company_user';

    /**
     * @var string
     */
    public const ID_SSP_INQUIRY = 'id_ssp_inquiry';

    /**
     * @var string
     */
    public const STATUS = 'status';

    /**
     * @var string
     */
    public const STORE = 'store';

    /**
     * @var string
     */
    public const FK_STORE = 'fk_store';
}
