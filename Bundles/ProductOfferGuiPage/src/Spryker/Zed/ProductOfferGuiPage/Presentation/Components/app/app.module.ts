import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';

import { ProductOfferModule } from './product-offer/product-offer.module';
import { ProductOfferComponent } from './product-offer/product-offer.component';

@NgModule({
    imports: [BrowserModule, ProductOfferModule],
    providers: []
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'mp-product-offer',
            component: ProductOfferComponent
        },
    ];
}
