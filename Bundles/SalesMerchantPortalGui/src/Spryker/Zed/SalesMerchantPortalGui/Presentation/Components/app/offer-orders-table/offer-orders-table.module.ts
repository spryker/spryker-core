import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule, TableDefaultConfig, TableConfig } from '@spryker/table';
import {
    TableColumnTextComponent,
    TableColumnTextModule,
    TableColumnImageComponent,
    TableColumnImageModule,
    TableColumnDateComponent,
    TableColumnDateModule,
    TableColumnChipComponent,
    TableColumnChipModule,
} from '@spryker/table/columns';
import { OfferOrdersTableComponent } from './offer-orders-table.component';
import { TableFiltersFeatureModule } from '@spryker/table/features';
import {
    TableFilterDateRangeComponent,
    TableFilterDateRangeModule,
    TableFilterSelectComponent,
    TableFilterSelectModule,
} from '@spryker/table/filters';
import { TableDatasourceHttpService } from '@spryker/table/datasources';
import { NotificationModule } from '@spryker/notification';
import { AjaxActionModule } from '@spryker/ajax-action';
import {
    AjaxPostActionCloseService,
    AjaxPostActionRedirectService,
    AjaxPostActionRefreshTableService,
} from '@spryker/ajax-post-actions';
import {
    TableHtmlOverlayActionHandlerModule,
    TableHtmlOverlayActionHandlerService
} from '@spryker/table/action-handlers';
import { DefaultContextSerializationModule } from '@spryker/utils';

class TableDefaultConfigData implements Partial<TableConfig> {
    total = {
        enabled: true,
    };
}

@NgModule({
    imports: [
        CommonModule,
        TableModule.forRoot(),
        TableColumnChipModule,
        TableColumnTextModule,
        TableColumnImageModule,
        TableColumnDateModule,
        TableFilterSelectModule,
        TableFilterDateRangeModule,
        TableModule.withFeatures({
            filters: () => import('@spryker/table/features').then(m => m.TableFiltersFeatureModule),
            pagination: () => import('@spryker/table/features').then(m => m.TablePaginationFeatureModule),
            rowActions: () => import('@spryker/table/features').then(m => m.TableRowActionsFeatureModule),
            search: () => import('@spryker/table/features').then(m => m.TableSearchFeatureModule),
            syncStateUrl: () => import('@spryker/table/features').then(m => m.TableSyncStateFeatureModule),
            total: () => import('@spryker/table/features').then(m => m.TableTotalFeatureModule),
            itemSelection: () => import('@spryker/table/features').then(m => m.TableSelectableFeatureModule),
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
            'html-overlay': TableHtmlOverlayActionHandlerService,
        }),
        NotificationModule.forRoot(),
        AjaxActionModule.withActions({
            close_overlay: AjaxPostActionCloseService,
            redirect: AjaxPostActionRedirectService,
            refresh_table: AjaxPostActionRefreshTableService,
        }),
        TableHtmlOverlayActionHandlerModule,
        DefaultContextSerializationModule,
    ],
    providers: [
        {
            provide: TableDefaultConfig,
            useClass: TableDefaultConfigData,
        },
    ],
    declarations: [
        OfferOrdersTableComponent,
    ],
    exports: [
        OfferOrdersTableComponent,
    ],
})
export class OfferOrdersTableModule {
}
