<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Shared\SspInquiryManagement;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class SspInquiryManagementConstants
{
    /**
     * Base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080)
     *
     * @api
     *
     * @var string
     */
    public const BASE_URL_YVES = 'SSP_INQUIRY_MANAGEMENT:BASE_URL_YVES';

    /**
     * @var string
     */
    public const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    public const DEFAULT_TOTAL_FILE_MAX_SIZE = 'SSP_INQUIRY_MANAGEMENT:DEFAULT_TOTAL_FILE_MAX_SIZE';

    /**
     * @var string
     */
    public const DEFAULT_FILE_MAX_SIZE = 'SSP_INQUIRY_MANAGEMENT:DEFAULT_FILE_MAX_SIZE';
}
