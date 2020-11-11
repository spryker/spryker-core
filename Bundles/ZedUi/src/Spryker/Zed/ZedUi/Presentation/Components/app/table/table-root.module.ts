import { NgModule } from '@angular/core';
import { TableModule, TableConfig, TableDefaultConfig } from '@spryker/table';
import {
    TableFormOverlayActionHandlerModule,
    TableFormOverlayActionHandlerService,
} from '@spryker/table.action-handler.form-overlay';
import {
    TableHtmlOverlayActionHandlerService,
    TableHtmlOverlayActionHandlerModule,
} from '@spryker/table.action-handler.html-overlay';
import {
    TableUrlActionHandlerModule,
    TableUrlActionHandlerService,
} from '@spryker/table.action-handler.url';
import { TableColumnChipComponent, TableColumnChipModule } from '@spryker/table.column.chip';
import { TableColumnDateComponent, TableColumnDateModule } from '@spryker/table.column.date';
import { TableColumnImageComponent, TableColumnImageModule } from '@spryker/table.column.image';
import { TableColumnTextComponent, TableColumnTextModule } from '@spryker/table.column.text';
import { TableColumnInputComponent, TableColumnInputModule } from '@spryker/table.column.input';
import { TableColumnSelectComponent, TableColumnSelectModule } from '@spryker/table.column.select';
import { TableDatasourceHttpService } from '@spryker/table.datasource.http';
import { TableFiltersFeatureModule } from '@spryker/table.feature.filters';
import { TableFilterDateRangeComponent, TableFilterDateRangeModule } from '@spryker/table.filter.date-range';
import { TableFilterSelectComponent, TableFilterSelectModule } from '@spryker/table.filter.select';
import { TableFilterTreeSelectComponent, TableFilterTreeSelectModule } from '@spryker/table.filter.tree-select';

export class TableDefaultConfigData implements Partial<TableConfig> {
    total = {
        enabled: true,
    };
    settings = {
        enabled: true,
    };
}

@NgModule({
    imports: [
        TableModule.forRoot(),
        TableModule.withFeatures({
            filters: () =>
                import('@spryker/table.feature.filters').then(
                    m => m.TableFiltersFeatureModule
                ),
            pagination: () =>
                import('@spryker/table.feature.pagination').then(
                    m => m.TablePaginationFeatureModule
                ),
            rowActions: () =>
                import('@spryker/table.feature.row-actions').then(
                    m => m.TableRowActionsFeatureModule
                ),
            search: () =>
                import('@spryker/table.feature.search').then(
                    m => m.TableSearchFeatureModule
                ),
            syncStateUrl: () =>
                import('@spryker/table.feature.sync-state').then(
                    m => m.TableSyncStateFeatureModule
                ),
            total: () =>
                import('@spryker/table.feature.total').then(
                    m => m.TableTotalFeatureModule
                ),
            itemSelection: () =>
                import('@spryker/table.feature.selectable').then(
                    m => m.TableSelectableFeatureModule
                ),
            batchActions: () =>
                import('@spryker/table.feature.batch-actions').then(
                    m => m.TableBatchActionsFeatureModule
                ),
            settings: () =>
                import('@spryker/table.feature.settings').then(
                    m => m.TableSettingsFeatureModule,
                ),
            title: () =>
                import('@spryker/table.feature.title').then(
                    m => m.TableTitleFeatureModule
                ),
            editable: () =>
                import('@spryker/table.feature.editable').then(
                    m => m.TableEditableFeatureModule
                ),
        }),
        TableModule.withColumnComponents({
            text: TableColumnTextComponent,
            image: TableColumnImageComponent,
            date: TableColumnDateComponent,
            chip: TableColumnChipComponent,
            input: TableColumnInputComponent,
            select: TableColumnSelectComponent,
        } as any),
        TableFiltersFeatureModule.withFilterComponents({
            select: TableFilterSelectComponent,
            'date-range': TableFilterDateRangeComponent,
            'tree-select': TableFilterTreeSelectComponent,
        } as any),
        TableModule.withDatasourceTypes({
            http: TableDatasourceHttpService,
        }),
        TableModule.withActions({
            'form-overlay': TableFormOverlayActionHandlerService,
            'html-overlay': TableHtmlOverlayActionHandlerService,
            'url': TableUrlActionHandlerService,
        }),

        // Table Column Type Modules
        TableColumnChipModule,
        TableColumnTextModule,
        TableColumnImageModule,
        TableColumnDateModule,
        TableColumnInputModule,
        TableColumnSelectModule,

        // Table Filter Modules
        TableFilterSelectModule,
        TableFilterDateRangeModule,
        TableFilterTreeSelectModule,

        // Table Action Handler Modules
        TableFormOverlayActionHandlerModule,
        TableHtmlOverlayActionHandlerModule,
        TableUrlActionHandlerModule,
    ],
    providers: [
        {
            provide: TableDefaultConfig,
            useClass: TableDefaultConfigData,
        },
    ],
})
export class TableRootModule {}
