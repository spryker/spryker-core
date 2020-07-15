import { NgModule } from '@angular/core';
import { TableModule, TableConfig, TableDefaultConfig } from '@spryker/table';
import {
    TableFormOverlayActionHandlerModule,
    TableFormOverlayActionHandlerService,
} from '@spryker/table/action-handlers';
import {
    TableColumnChipComponent,
    TableColumnChipModule,
    TableColumnDateComponent,
    TableColumnDateModule,
    TableColumnImageComponent,
    TableColumnImageModule,
    TableColumnTextComponent,
    TableColumnTextModule,
} from '@spryker/table/columns';
import { TableDatasourceHttpService } from '@spryker/table/datasources';
import { TableFiltersFeatureModule } from '@spryker/table/features';
import {
    TableFilterDateRangeComponent,
    TableFilterDateRangeModule,
    TableFilterSelectComponent,
    TableFilterSelectModule,
} from '@spryker/table/filters';

export class TableDefaultConfigData implements Partial<TableConfig> {
    total = {
        enabled: true,
    };
}

@NgModule({
    imports: [
        TableModule.forRoot(),
        TableModule.withFeatures({
            filters: () =>
                import('@spryker/table/features').then(
                    (m) => m.TableFiltersFeatureModule
                ),
            pagination: () =>
                import('@spryker/table/features').then(
                    (m) => m.TablePaginationFeatureModule
                ),
            rowActions: () =>
                import('@spryker/table/features').then(
                    (m) => m.TableRowActionsFeatureModule
                ),
            search: () =>
                import('@spryker/table/features').then(
                    (m) => m.TableSearchFeatureModule
                ),
            syncStateUrl: () =>
                import('@spryker/table/features').then(
                    (m) => m.TableSyncStateFeatureModule
                ),
            total: () =>
                import('@spryker/table/features').then(
                    (m) => m.TableTotalFeatureModule
                ),
            itemSelection: () =>
                import('@spryker/table/features').then(
                    (m) => m.TableSelectableFeatureModule
                ),
        }),
        TableModule.withColumnComponents({
            text: TableColumnTextComponent,
            image: TableColumnImageComponent,
            date: TableColumnDateComponent,
            chip: TableColumnChipComponent,
        } as any),
        TableFiltersFeatureModule.withFilterComponents({
            select: TableFilterSelectComponent,
            'date-range': TableFilterDateRangeComponent,
        } as any),
        TableModule.withDatasourceTypes({
            http: TableDatasourceHttpService,
        }),
        TableModule.withActions({
            'form-overlay': TableFormOverlayActionHandlerService,
        }),

        // Table Column Type Modules
        TableColumnChipModule,
        TableColumnTextModule,
        TableColumnImageModule,
        TableColumnDateModule,
        TableFilterSelectModule,
        TableFilterDateRangeModule,

        // Table Action Handler Modules
        TableFormOverlayActionHandlerModule,
    ],
    providers: [
        {
            provide: TableDefaultConfig,
            useClass: TableDefaultConfigData,
        },
    ],
})
export class TableRootModule {}
