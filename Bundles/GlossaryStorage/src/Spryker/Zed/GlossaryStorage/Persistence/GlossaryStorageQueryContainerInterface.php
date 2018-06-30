<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Persistence;

interface GlossaryStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $glossaryKeyIds
     *
     * @return \Orm\Zed\GlossaryStorage\Persistence\SpyGlossaryStorageQuery
     */
    public function queryGlossaryStorageByGlossaryIds(array $glossaryKeyIds);

    /**
     * @api
     *
     * @param array $glossaryKeyIds
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryGlossaryTranslation(array $glossaryKeyIds);

    /**
     * @api
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryGlossaryKeys();
}
