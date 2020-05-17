import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';
import {
    TableColumnTextComponent,
    TableColumnTextModule,
    TableColumnImageComponent,
    TableColumnImageModule,
    TableColumnDateComponent,
    TableColumnDateModule,
} from '@spryker/table/columns';
import { ProductOfferTableComponent } from './product-offer-table.component';
import {
    TableSearchFeatureComponent,
    TableSearchFeatureModule,
    TableFiltersFeatureComponent,
    TableFiltersFeatureModule,
    TableSyncStateFeatureComponent,
    TableSyncStateFeatureModule
} from '@spryker/table/features';
import {
    TableFilterSelectComponent,
    TableFilterSelectModule
} from '@spryker/table/filters';
import { TableDatasourceHttpService } from '@spryker/table/datasources';

@NgModule({
    imports: [
        CommonModule,
        TableColumnTextModule,
        TableColumnImageModule,
        TableColumnDateModule,
        TableSearchFeatureModule,
        TableFiltersFeatureModule,
        TableFilterSelectModule,
        TableSyncStateFeatureModule,
        TableModule.forRoot(),
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
            date: TableColumnDateComponent
        } as any),
        TableFiltersFeatureModule.withFilterComponents({
            select: TableFilterSelectComponent,
        } as any),
        TableModule.withDatasourceTypes({
            http: TableDatasourceHttpService,
        }),
    ],
    declarations: [
        ProductOfferTableComponent
    ],
    exports: [
        ProductOfferTableComponent
    ],
})
export class ProductOfferTableModule {
}
