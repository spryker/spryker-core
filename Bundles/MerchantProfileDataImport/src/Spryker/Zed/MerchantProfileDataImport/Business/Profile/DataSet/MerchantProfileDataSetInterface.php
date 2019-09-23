<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileDataImport\Business\Profile\DataSet;

interface MerchantProfileDataSetInterface
{
    public const ID_MERCHANT = 'id_merhcnat';
    public const MERCHANT_KEY = 'merchant_key';

    public const CONTENT_PERSON_ROLE = 'contact_person_role';
    public const CONTENT_PERSON_TITLE = 'contact_person_title';
    public const CONTENT_PERSON_FIRST_NAME = 'contact_person_first_name';
    public const CONTENT_PERSON_LAST_NAME = 'contact_person_last_name';
    public const CONTENT_PERSON_PHONE = 'contact_person_phone';
    public const BANNER_URL = 'banner_url';
    public const LOGO_URL = 'logo_url';
    public const PUBLIC_EMAIL = 'public_email';
    public const IS_ACTIVE = 'is_active';

    public const DESCRIPTION_GLOSSARY_KEY = 'description_glossary_key';
    public const BANNER_URL_GLOSSARY_KEY = 'banner_url_glossary_key';
    public const DELIVERY_TIME_GLOSSARY = 'delivery_time_glossary_key';
    public const TERMS_CONDITIONS_GLOSSARY_KEY = 'terms_conditions_glossary_key';
    public const CANCELLATION_POLICY_GLOSSARY_KEY = 'cancellation_policy_glossary_key';
    public const IMPRINT_GLOSSARY_KEY = 'imprint_glossary_key';
    public const DATA_PRIVACY_GLOSSARY_KEY = 'data_privacy_glossary_key';
}
