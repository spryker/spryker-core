<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency;

class DataImportEvents
{

    const BEFORE_IMPORT = 'DataImport.before.import';
    const AFTER_IMPORT = 'DataImport.after.import';

    const BEFORE_DATA_SET_IMPORT = 'DataImport.before.data-set-import';
    const AFTER_DATA_SET_IMPORT = 'DataImport.after.data-set-import';

    const BEFORE_DATA_SET_IMPORTER = 'DataImport.before.data-set-importer';
    const AFTER_DATA_SET_IMPORTER = 'DataImport.after.data-set-importer';

}
