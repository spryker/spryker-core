<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipDataImport\Business\Model\DataSet;

interface MerchantRelationshipDataSet
{
    public const MERCHANT_RELATIONSHIP_KEY = 'merchant_relation_key';
    public const MERCHANT_KEY = 'mechant_key';
    public const COMPANY_BUSINESS_UNIT_OWNER_KEY = 'company_business_unit_owner_key';
    public const COMPANY_BUSINESS_UNIT_ASSIGNEE_KEYS = 'company_business_unit_assignee_keys';
    public const ID_MERCHANT = 'idMerchant';
    public const ID_COMPANY_BUSINESS_UNIT = 'idCompanyBusinessUnit';
    public const ID_COMPANY_BUSINESS_UNIT_ASSIGNEE_COLLECTION = 'idCompanyBusinessUnitAssigneeCollection';
}
