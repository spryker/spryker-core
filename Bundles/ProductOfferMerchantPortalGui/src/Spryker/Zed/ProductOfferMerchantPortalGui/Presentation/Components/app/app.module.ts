import { BrowserModule } from '@angular/platform-browser';
import { NgModule, LOCALE_ID } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { TableModule } from '@spryker/table';
import { ProductOfferModule } from './product-offer/product-offer.module';
import { ProductOfferComponent } from './product-offer/product-offer.component';
import { OffersListModule } from './offers-list/offers-list.module';
import { OffersListComponent } from './offers-list/offers-list.component';
import { LocaleModule } from '@spryker/locale';
import { EN_LOCALE, EnLocaleModule } from '@spryker/locale/locales/en';
import { DeLocaleModule } from '@spryker/locale/locales/de';

// TODO: Remove after locale module will be fixed
import { registerLocaleData } from '@angular/common';
import en from '@angular/common/locales/en';
import { NZ_I18N, en_US } from 'ng-zorro-antd/i18n';

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
    ],
    providers: [
        // TODO: Remove after locale module will be fixed
        {
            provide: NZ_I18N,
            useValue: en_US,
        },
    ],
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

// TODO: Remove after locale module will be fixed
registerLocaleData(en);

