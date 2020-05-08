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
        TableModule,
        TableModule.withColumnComponents({
            text: TableColumnTextComponent,
            image: TableColumnImageComponent,
            date: TableColumnDateComponent
        } as any),
        TableFiltersFeatureModule.withFilterComponents({
            select: TableFilterSelectComponent as any,
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
