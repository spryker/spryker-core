<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipDataImport\Business\Model\DataSet;

interface MerchantRelationshipDataSetInterface
{
    public const MERCHANT_RELATIONSHIP_KEY = 'merchant_relation_key';
    public const MERCHANT_KEY = 'merchant_key';
    public const COMPANY_BUSINESS_UNIT_OWNER_KEY = 'company_business_unit_owner_key';
    public const COMPANY_BUSINESS_UNIT_ASSIGNEE_KEYS = 'company_business_unit_assignee_keys';

    public const ID_MERCHANT = 'id_merchant';
    public const ID_COMPANY_BUSINESS_UNIT = 'id_company_business_unit';
    public const ID_COMPANY_BUSINESS_UNIT_ASSIGNEE_COLLECTION = 'id_company_business_unit_assignee_collection';
}
