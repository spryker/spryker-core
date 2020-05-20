import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { TableModule } from '@spryker/table';
import { ProductOfferModule } from './product-offer/product-offer.module';
import { ProductOfferComponent } from './product-offer/product-offer.component';
import { OffersListModule } from './offers-list/offers-list.module';
import { OffersListComponent } from './offers-list/offers-list.component';

@NgModule({
    imports: [
        BrowserModule,
        HttpClientModule,
        BrowserAnimationsModule,
        ProductOfferModule,
        OffersListModule,
    ],
    providers: []
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
