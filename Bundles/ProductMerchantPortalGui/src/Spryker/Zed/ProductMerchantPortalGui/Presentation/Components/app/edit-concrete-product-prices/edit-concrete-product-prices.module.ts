import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';
import { EditConcreteProductPricesComponent } from './edit-concrete-product-prices.component';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [EditConcreteProductPricesComponent],
    exports: [EditConcreteProductPricesComponent],
})
export class EditConcreteProductPricesModule {}
