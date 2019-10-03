<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProfileDataImport\Business\Profile\DataSet;

interface MerchantProfileDataSetInterface
{
    public const ID_MERCHANT = 'id_merhcnat';
    public const MERCHANT_KEY = 'merchant_key';

    public const DESCRIPTION_GLOSSARY_KEY = 'description_glossary_key';
    public const BANNER_URL_GLOSSARY_KEY = 'banner_url_glossary_key';
    public const DELIVERY_TIME_GLOSSARY = 'delivery_time_glossary_key';
    public const TERMS_CONDITIONS_GLOSSARY_KEY = 'terms_conditions_glossary_key';
    public const CANCELLATION_POLICY_GLOSSARY_KEY = 'cancellation_policy_glossary_key';
    public const IMPRINT_GLOSSARY_KEY = 'imprint_glossary_key';
    public const DATA_PRIVACY_GLOSSARY_KEY = 'data_privacy_glossary_key';
}
