import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { TableModule } from '@spryker/table';
import { ProductOfferModule } from './product-offer/product-offer.module';
import { ProductOfferComponent } from './product-offer/product-offer.component';

@NgModule({
    imports: [
        BrowserModule,
        HttpClientModule,
        BrowserAnimationsModule,
        ProductOfferModule,
        TableModule.forRoot(),
    ],
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
