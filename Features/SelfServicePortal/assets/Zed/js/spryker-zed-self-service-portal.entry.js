import '../sass/main.scss';
import { Table } from 'ZedGuiModules/libs/table/table';
import { InternalGuardTabsState } from './internal-api/internal-guard-tabs-state';
import { InternalSkeletonTable } from './internal-api/internal-skeleton-table';

Table.FEATURES = {
    ...Table.FEATURES,
    skeleton: {
        attribute: 'data-skeleton',
        class: InternalSkeletonTable,
    },
};

document.addEventListener('DOMContentLoaded', function () {
    new InternalGuardTabsState();
});
