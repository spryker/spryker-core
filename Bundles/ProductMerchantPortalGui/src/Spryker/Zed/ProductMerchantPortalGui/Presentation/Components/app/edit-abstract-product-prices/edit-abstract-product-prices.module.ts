import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EditAbstractProductPricesComponent } from './edit-abstract-product-prices.component';
import { TableModule } from '@spryker/table';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [EditAbstractProductPricesComponent],
    exports: [EditAbstractProductPricesComponent],
})
export class EditAbstractProductPricesModule {}
