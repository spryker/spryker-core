import { NgModule, Injectable } from '@angular/core';
import { TableModule, TableConfig, TableDefaultConfig, TableFeatureConfig } from '@spryker/table';
import {
    TableColumnAutocompleteComponent,
    TableColumnAutocompleteModule,
    TableColumnAutocompleteConfig,
} from '@spryker/table.column.autocomplete';
import { TableColumnChipComponent, TableColumnChipModule, TableColumnChipConfig } from '@spryker/table.column.chip';
import { TableColumnDateComponent, TableColumnDateModule, TableColumnDateConfig } from '@spryker/table.column.date';
import {
    TableColumnDynamicComponent,
    TableColumnDynamicModule,
    TableColumnDynamicConfig,
} from '@spryker/table.column.dynamic';
import { TableColumnImageComponent, TableColumnImageModule, TableColumnImageConfig } from '@spryker/table.column.image';
import { TableColumnInputComponent, TableColumnInputModule, TableColumnInputConfig } from '@spryker/table.column.input';
import {
    TableColumnSelectComponent,
    TableColumnSelectModule,
    TableColumnSelectConfig,
} from '@spryker/table.column.select';
import { TableColumnTextComponent, TableColumnTextModule, TableColumnTextConfig } from '@spryker/table.column.text';
import {
    TableFilterDateRangeComponent,
    TableFilterDateRangeModule,
    TableFilterDateRange,
} from '@spryker/table.filter.date-range';
import { TableFilterSelectComponent, TableFilterSelectModule, TableFilterSelect } from '@spryker/table.filter.select';
import {
    TableFilterTreeSelectComponent,
    TableFilterTreeSelectModule,
    TableFilterTreeSelect,
} from '@spryker/table.filter.tree-select';
import { TableFiltersFeatureModule, TableFiltersConfig } from '@spryker/table.feature.filters';
import { TablePaginationConfig } from '@spryker/table.feature.pagination';
import { TableRowActionsConfig } from '@spryker/table.feature.row-actions';
import { TableSearchConfig } from '@spryker/table.feature.search';
import { TableSyncStateConfig } from '@spryker/table.feature.sync-state';
import { TableTotalConfig } from '@spryker/table.feature.total';
import { TableSelectableConfig } from '@spryker/table.feature.selectable';
import { TableBatchActionsConfig } from '@spryker/table.feature.batch-actions';
import { TableSettingsConfig } from '@spryker/table.feature.settings';
import { TableEditableConfig } from '@spryker/table.feature.editable';

@Injectable()
class TableDefaultConfigData implements Partial<TableConfig> {
    total = {
        enabled: true,
    };
    columnConfigurator = {
        enabled: true,
    };
    [featureName: string]: TableFeatureConfig | unknown;
}

declare module '@spryker/table' {
    interface TableConfig {
        filters?: TableFiltersConfig;
        pagination?: TablePaginationConfig;
        rowActions?: TableRowActionsConfig;
        search?: TableSearchConfig;
        syncStateUrl?: TableSyncStateConfig;
        total?: TableTotalConfig;
        itemSelection?: TableSelectableConfig;
        batchActions?: TableBatchActionsConfig;
        columnConfigurator?: TableSettingsConfig;
        editable?: TableEditableConfig;
    }
}

declare module '@spryker/table' {
    interface TableColumnTypeRegistry {
        text: TableColumnTextConfig;
        image: TableColumnImageConfig;
        date: TableColumnDateConfig;
        chip: TableColumnChipConfig;
        input: TableColumnInputConfig;
        select: TableColumnSelectConfig;
        dynamic: TableColumnDynamicConfig;
        autocomplete: TableColumnAutocompleteConfig;
    }
}

declare module '@spryker/table.feature.filters' {
    interface TableFiltersRegistry {
        select: TableFilterSelect;
        'date-range': TableFilterDateRange;
        'tree-select': TableFilterTreeSelect;
    }
}

@NgModule({
    imports: [
        TableModule.forRoot(),
        TableModule.withFeatures({
            filters: () => import('@spryker/table.feature.filters').then((m) => m.TableFiltersFeatureModule),
            pagination: () => import('@spryker/table.feature.pagination').then((m) => m.TablePaginationFeatureModule),
            rowActions: () => import('@spryker/table.feature.row-actions').then((m) => m.TableRowActionsFeatureModule),
            search: () => import('@spryker/table.feature.search').then((m) => m.TableSearchFeatureModule),
            syncStateUrl: () => import('@spryker/table.feature.sync-state').then((m) => m.TableSyncStateFeatureModule),
            total: () => import('@spryker/table.feature.total').then((m) => m.TableTotalFeatureModule),
            itemSelection: () =>
                import('@spryker/table.feature.selectable').then((m) => m.TableSelectableFeatureModule),
            batchActions: () =>
                import('@spryker/table.feature.batch-actions').then((m) => m.TableBatchActionsFeatureModule),
            columnConfigurator: () =>
                import('@spryker/table.feature.settings').then((m) => m.TableSettingsFeatureModule),
            title: () => import('@spryker/table.feature.title').then((m) => m.TableTitleFeatureModule),
            editable: () => import('@spryker/table.feature.editable').then((m) => m.TableEditableFeatureModule),
        }),
        TableModule.withColumnComponents({
            text: TableColumnTextComponent,
            image: TableColumnImageComponent,
            date: TableColumnDateComponent,
            chip: TableColumnChipComponent,
            input: TableColumnInputComponent,
            select: TableColumnSelectComponent,
            dynamic: TableColumnDynamicComponent,
            autocomplete: TableColumnAutocompleteComponent,
        }),
        TableFiltersFeatureModule.withFilterComponents({
            select: TableFilterSelectComponent,
            'date-range': TableFilterDateRangeComponent,
            'tree-select': TableFilterTreeSelectComponent,
        }),

        // Table Column Type Modules
        TableColumnChipModule,
        TableColumnTextModule,
        TableColumnImageModule,
        TableColumnDateModule,
        TableColumnInputModule,
        TableColumnSelectModule,
        TableColumnDynamicModule,
        TableColumnAutocompleteModule,

        // Table Filter Modules
        TableFilterSelectModule,
        TableFilterDateRangeModule,
        TableFilterTreeSelectModule,
    ],
    providers: [
        {
            provide: TableDefaultConfig,
            useClass: TableDefaultConfigData,
        },
    ],
})
export class DefaultTableConfigModule {}
