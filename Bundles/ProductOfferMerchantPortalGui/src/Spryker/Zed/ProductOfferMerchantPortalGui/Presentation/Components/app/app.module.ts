import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { ButtonLinkModule, ButtonLinkComponent } from '@spryker/button';
import { CustomElementModule } from '@spryker/web-components';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ProductOfferModule } from './product-offer/product-offer.module';
import { ProductOfferComponent } from './product-offer/product-offer.component';
import { OffersListModule } from './offers-list/offers-list.module';
import { OffersListComponent } from './offers-list/offers-list.component';
import { LocaleModule, LocaleSwitcherComponent } from '@spryker/locale';
import { EnLocaleModule } from '@spryker/locale/locales/en';
import { DeLocaleModule } from '@spryker/locale/locales/de';
import { TableModule, TableConfig, TableDefaultConfig } from '@spryker/table';
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
import { TableFormOverlayActionHandlerService, TableFormOverlayActionHandlerModule } from '@spryker/table/action-handlers';
import { TableDatasourceHttpService } from '@spryker/table/datasources';
import { EditOfferModule } from './edit-offer/edit-offer.module';
import { EditOfferComponent } from './edit-offer/edit-offer.component';
import { ButtonModule, ButtonComponent} from '@spryker/button';
import { ChipsModule, ChipsComponent } from '@spryker/chips';
import { ToggleModule, ToggleComponent } from '@spryker/toggle';
import { CardModule, CardComponent } from '@spryker/card';
import { SelectModule, SelectComponent } from '@spryker/select';
import { InputModule, InputComponent } from '@spryker/input';
import { CheckboxModule, CheckboxComponent } from '@spryker/checkbox';
import { DateRangePickerModule, DateRangePickerComponent } from '@spryker/date-picker';
import { CollapsibleModule, CollapsibleComponent } from '@spryker/collapsible';
import { FormItemModule, FormItemComponent } from '@spryker/form-item';
import { NotificationModule, NotificationComponent } from '@spryker/notification';
import { AjaxActionModule } from '@spryker/ajax-action';
import {
    AjaxPostActionCloseService,
    AjaxPostActionRedirectService,
    AjaxPostActionRefreshTableService,
} from '@spryker/ajax-post-actions';

class TableDefaultConfigData implements Partial<TableConfig> {
    total = {
        enabled: true,
    };
}

@NgModule({
    imports: [
        ButtonLinkModule,
        FormItemModule,
        CollapsibleModule,
        CardModule,
        DateRangePickerModule,
        CheckboxModule,
        InputModule,
        SelectModule,
        ToggleModule,
        ChipsModule,
        ButtonModule,
        EditOfferModule,
        BrowserModule,
        HttpClientModule,
        BrowserAnimationsModule,
        ProductOfferModule,
        OffersListModule,
        LocaleModule.forRoot(),
        EnLocaleModule,
        DeLocaleModule,
        TableModule.forRoot(),
        TableColumnChipModule,
        TableColumnTextModule,
        TableColumnImageModule,
        TableColumnDateModule,
        TableFilterSelectModule,
        TableFilterDateRangeModule,
        TableFormOverlayActionHandlerModule,
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
            'form-overlay': TableFormOverlayActionHandlerService,
        }),
        NotificationModule.forRoot(),
        AjaxActionModule.withActions({
            close_overlay: AjaxPostActionCloseService,
            redirect: AjaxPostActionRedirectService,
            refresh_table: AjaxPostActionRefreshTableService,
        }),
    ],
    providers: [
        {
            provide: TableDefaultConfig,
            useClass: TableDefaultConfigData,
        },
    ],
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'web-mp-offers-list',
            component: OffersListComponent,
        },
        {
            selector: 'web-mp-product-offer',
            component: ProductOfferComponent,
        },
        {
            selector: 'web-spy-button-link',
            component: ButtonLinkComponent,
        },
        {
            selector: 'web-spy-locale-switcher',
            component: LocaleSwitcherComponent,
        }
        {
            selector: 'web-mp-edit-offer',
            component: EditOfferComponent,
        },
        {
            selector: 'web-spy-button',
            component: ButtonComponent,
        },
        {
            selector: 'web-spy-chips',
            component: ChipsComponent,
        },
        {
            selector: 'web-spy-toggle',
            component: ToggleComponent,
        },
        {
            selector: 'web-spy-input',
            component: InputComponent,
        },
        {
            selector: 'web-spy-select',
            component: SelectComponent,
        },
        {
            selector: 'web-spy-card',
            component: CardComponent,
        },
        {
            selector: 'web-spy-date-range-picker',
            component: DateRangePickerComponent,
        },
        {
            selector: 'web-spy-checkbox',
            component: CheckboxComponent,
        },
        {
            selector: 'web-spy-collapsible',
            component: CollapsibleComponent,
        },
        {
            selector: 'web-spy-form-item',
            component: FormItemComponent,
        },
    ];
}

