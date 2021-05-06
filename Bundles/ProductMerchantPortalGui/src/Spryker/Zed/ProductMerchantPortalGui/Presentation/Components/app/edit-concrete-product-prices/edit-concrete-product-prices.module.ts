import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';
import { CheckboxModule } from '@spryker/checkbox';
import { CardModule } from '@spryker/card';
import { EditConcreteProductPricesComponent } from './edit-concrete-product-prices.component';

@NgModule({
    imports: [CommonModule, TableModule, CheckboxModule, CardModule],
    declarations: [EditConcreteProductPricesComponent],
    exports: [EditConcreteProductPricesComponent],
})
export class EditConcreteProductPricesModule {}
