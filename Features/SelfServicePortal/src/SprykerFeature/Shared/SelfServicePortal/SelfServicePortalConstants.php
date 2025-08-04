<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Shared\SelfServicePortal;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class SelfServicePortalConstants
{
    /**
     * Specification
     * - Defines the mapping of payment method names to their respective state machine processes.
     * - Used to define the payment method during the updating sales order item in backoffice.
     *
     * @api
     *
     * @var string
     */
    public const PAYMENT_METHOD_STATEMACHINE_MAPPING = 'SELF_SERVICE_PORTAL:PAYMENT_METHOD_STATEMACHINE_MAPPING';

    /**
     * Specification:
     * - Defines the storage name for Self Service Portal company files related data.
     *
     * @api
     *
     * @var string
     */
    public const STORAGE_NAME = 'SELF_SERVICE_PORTAL:STORAGE_NAME';

    /**
     * Specification:
     * - Defines the storage name for Self Service Portal inquiry files related data.
     *
     * @api
     *
     * @var string
     */
    public const INQUIRY_STORAGE_NAME = 'SELF_SERVICE_PORTAL:INQUIRY_STORAGE_NAME';

    /**
     * Specification:
     * - Defines the storage name for Self Service Portal asset image files related data.
     *
     * @api
     *
     * @var string
     */
    public const ASSET_STORAGE_NAME = 'SELF_SERVICE_PORTAL:ASSET_STORAGE_NAME';

    /**
     * Base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080)
     *
     * @api
     *
     * @var string
     */
    public const BASE_URL_YVES = 'SELF_SERVICE_PORTAL:BASE_URL_YVES';

    /**
     * Specification:
     * - Returns the default total file max size for file uploads.
     *
     * @api
     *
     * @var string
     */
    public const DEFAULT_TOTAL_FILE_MAX_SIZE = 'SELF_SERVICE_PORTAL:DEFAULT_TOTAL_FILE_MAX_SIZE';

    /**
     * Specification:
     * - Returns the default file max size per file upload.
     *
     * @api
     *
     * @var string
     */
    public const DEFAULT_FILE_MAX_SIZE = 'SELF_SERVICE_PORTAL:DEFAULT_FILE_MAX_SIZE';

    /**
     * Base URL for Backoffice including scheme and port (e.g. http://www.de.demoshop.local:8080)
     *
     * @api
     *
     * @var string
     */
    public const BASE_URL_BACKOFFICE = 'SELF_SERVICE_PORTAL:BASE_URL_BACKOFFICE';

    /**
     * Specification:
     * - Defines the Google Maps API key.
     *
     * @api
     *
     * @var string
     */
    public const GOOGLE_MAPS_API_KEY = 'SELF_SERVICE_PORTAL:GOOGLE_MAPS_API_KEY';

    /**
     * Specification:
     * - Defines the storage name for Self Service Portal model images.
     *
     * @api
     *
     * @var string
     */
    public const SSP_MODEL_IMAGE_STORAGE_NAME = 'SELF_SERVICE_PORTAL:SSP_MODEL_IMAGE_STORAGE_NAME';
}
