<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Shared\SspFileManagement;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SspFileManagementConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Entity type for company.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_TYPE_COMPANY = 'company';

    /**
     * Specification:
     * - Entity type for company user.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_TYPE_COMPANY_USER = 'company_user';

    /**
     * Specification:
     * - Entity type for company business unit.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_TYPE_COMPANY_BUSINESS_UNIT = 'company_business_unit';

    /**
     * @uses \Spryker\Shared\UtilDateTime\UtilDateTimeConstants::DATE_TIME_ZONE
     *
     * @var string
     */
    protected const DATE_TIME_ZONE = 'DATE_TIME_ZONE';

    /**
     * @uses \Spryker\Service\UtilDateTime\Model\DateTimeFormatter::DEFAULT_TIME_ZONE
     *
     * @var string
     */
    protected const DEFAULT_TIME_ZONE = 'Europe/Berlin';

    /**
     * @api
     *
     * @return list<string>
     */
    public function getEntityTypes(): array
    {
        return [
            static::ENTITY_TYPE_COMPANY_USER,
            static::ENTITY_TYPE_COMPANY,
            static::ENTITY_TYPE_COMPANY_BUSINESS_UNIT,
        ];
    }

    /**
     * Specification:
     * - Returns date time zone.
     * - Used for filtering files by date.
     *
     * @api
     *
     * @return string
     */
    public function getDateTimeZone(): string
    {
        return $this->get(static::DATE_TIME_ZONE, static::DEFAULT_TIME_ZONE);
    }
}
