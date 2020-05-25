<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\Hook;

use Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;

class ProductLabelAfterImportPublishHook implements DataImporterAfterImportInterface
{
    /**
     * @uses \Spryker\Shared\ProductLabelStorage\ProductLabelStorageConfig::PRODUCT_LABEL_DICTIONARY_PUBLISH
     */
    protected const EVENT_PRODUCT_LABEL_DICTIONARY_PUBLISH = 'ProductLabel.product_label_dictionary.publish';

    protected const ID_DEFAULT = 0;

    /**
     * @return void
     */
    public function afterImport(): void
    {
        DataImporterPublisher::addEvent(static::EVENT_PRODUCT_LABEL_DICTIONARY_PUBLISH, static::ID_DEFAULT);
    }
}
