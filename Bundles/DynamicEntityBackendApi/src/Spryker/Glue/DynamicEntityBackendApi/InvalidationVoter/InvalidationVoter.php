<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\InvalidationVoter;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;
use Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToStorageFacadeInterface;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;

class InvalidationVoter implements InvalidationVoterInterface
{
    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected const CREATED_AT = 'created_at';

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface
     */
    protected $dynamicEntityFacade;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig
     */
    protected DynamicEntityBackendApiConfig $dynamicEntityBackendApiConfig;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToStorageFacadeInterface
     */
    protected DynamicEntityBackendApiToStorageFacadeInterface $storageFacade;

    /**
     * @param \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface $dynamicEntityFacade
     * @param \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig $dynamicEntityBackendApiConfig
     * @param \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToStorageFacadeInterface $storageFacade
     */
    public function __construct(
        DynamicEntityBackendApiToDynamicEntityFacadeInterface $dynamicEntityFacade,
        DynamicEntityBackendApiConfig $dynamicEntityBackendApiConfig,
        DynamicEntityBackendApiToStorageFacadeInterface $storageFacade
    ) {
        $this->dynamicEntityFacade = $dynamicEntityFacade;
        $this->dynamicEntityBackendApiConfig = $dynamicEntityBackendApiConfig;
        $this->storageFacade = $storageFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer
     *
     * @return bool
     */
    public function isInvalidated(DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer): bool
    {
        $fileCreatedDateTime = $this->resolveFileCreatedDateTime();

        if ($fileCreatedDateTime === null) {
            return true;
        }
        $dateTimeFromInterval = $this->resolveDateTimeFromInterval($documentationInvalidationVoterRequestTransfer);
        $oldestDateTime = $fileCreatedDateTime;

        if ($dateTimeFromInterval !== null) {
            /** @var \DateTime $oldestDateTime */
            $oldestDateTime = min($fileCreatedDateTime, $dateTimeFromInterval);
        }

        return $this->hasUpdatedConfigurations($oldestDateTime);
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return bool
     */
    protected function hasUpdatedConfigurations(DateTime $dateTime): bool
    {
        $dynamicEntityConfigurationCriteriaTransfer = (new DynamicEntityConfigurationCriteriaTransfer())->setDynamicEntityConfigurationConditions(
            (new DynamicEntityConfigurationConditionsTransfer())->setFilterUpdatedAt(
                (new CriteriaRangeFilterTransfer())->setFrom($dateTime->format(static::DATE_TIME_FORMAT)),
            ),
        );

        return $this->dynamicEntityFacade
                ->getDynamicEntityConfigurationCollection($dynamicEntityConfigurationCriteriaTransfer)
                ->getDynamicEntityConfigurations()
                ->count() > 0;
    }

    /**
     * @return \DateTime|null
     */
    protected function resolveFileCreatedDateTime(): ?DateTime
    {
        $backendApiSchemaStorageKey = $this->dynamicEntityBackendApiConfig->getBackendApiSchemaStorageKey();
        $backendApiSchemaData = $this->storageFacade->get($backendApiSchemaStorageKey);

        if ($backendApiSchemaData === null) {
            return null;
        }

        return (new DateTime())->setTimestamp($backendApiSchemaData[static::CREATED_AT]);
    }

    /**
     * @param \Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer
     *
     * @return \DateTime|null
     */
    protected function resolveDateTimeFromInterval(DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer): ?DateTime
    {
        if ($documentationInvalidationVoterRequestTransfer->getInterval() === null) {
            return null;
        }

        /** @var \DateInterval $dateInterval */
        $dateInterval = DateInterval::createFromDateString($documentationInvalidationVoterRequestTransfer->getInterval());

        return (new DateTime())->sub($dateInterval);
    }
}
