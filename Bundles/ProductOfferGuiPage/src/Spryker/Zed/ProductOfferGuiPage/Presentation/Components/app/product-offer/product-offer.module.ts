import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';
import { TableColumnTextComponent, TableColumnTextModule } from '@spryker/table/columns';
import { ProductOfferComponent } from './product-offer.component';

@NgModule({
    imports: [
        CommonModule,
        TableColumnTextModule,
        TableModule,
        TableModule.forRoot(),
        TableModule.withColumnComponents({
            text: TableColumnTextComponent,
        } as any),
    ],
    declarations: [
        ProductOfferComponent
    ],
    exports: [
        ProductOfferComponent
    ],
})
export class ProductOfferModule {
}
