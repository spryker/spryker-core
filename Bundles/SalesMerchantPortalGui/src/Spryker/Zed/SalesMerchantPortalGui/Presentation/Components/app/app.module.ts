import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';
import { LocaleModule } from '@spryker/locale';
import { EN_LOCALE, EnLocaleModule } from '@spryker/locale/locales/en';
import { DeLocaleModule } from '@spryker/locale/locales/de';
import { OfferOrdersComponent } from './offer-orders/offer-orders.component';
import { OfferOrdersModule } from './offer-orders/offer-orders.module';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

@NgModule({
  imports: [
    BrowserModule,
    HttpClientModule,
    BrowserAnimationsModule,
    LocaleModule.forRoot({ defaultLocale: EN_LOCALE }),
    EnLocaleModule,
    DeLocaleModule,
    OfferOrdersModule,
  ],
  providers: [],
})
export class AppModule extends CustomElementModule {
  protected components = [
    {
        selector: 'web-mp-offer-orders',
        component: OfferOrdersComponent,
    },
  ];
}
