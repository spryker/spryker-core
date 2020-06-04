import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ProductOfferModule } from './product-offer/product-offer.module';
import { ProductOfferComponent } from './product-offer/product-offer.component';
import { OffersListModule } from './offers-list/offers-list.module';
import { OffersListComponent } from './offers-list/offers-list.component';
import { LocaleModule } from '@spryker/locale';
import { EN_LOCALE, EnLocaleModule } from '@spryker/locale/locales/en';
import { DeLocaleModule } from '@spryker/locale/locales/de';
import { TableModule } from '@spryker/table';
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
import { TableFiltersFeatureModule } from '@spryker/table/features';
import {
    TableFilterSelectComponent,
    TableFilterSelectModule,
    TableFilterDateRangeComponent,
    TableFilterDateRangeModule,
} from '@spryker/table/filters';
import { TableDatasourceHttpService } from '@spryker/table/datasources';

@NgModule({
    imports: [
        BrowserModule,
        HttpClientModule,
        BrowserAnimationsModule,
        ProductOfferModule,
        OffersListModule,
        LocaleModule.forRoot({ defaultLocale: EN_LOCALE }),
        EnLocaleModule,
        DeLocaleModule,
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
            date_range: TableFilterDateRangeComponent,
        } as any),
        TableModule.withDatasourceTypes({
            http: TableDatasourceHttpService,
        }),
    ],
    providers: [],
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'mp-offers-list',
            component: OffersListComponent,
        },
        {
            selector: 'mp-product-offer',
            component: ProductOfferComponent,
        },
    ];
}

