import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TableModule } from '@spryker/table';
import { EditConcreteProductAttributesComponent } from './edit-concrete-product-attributes.component';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [EditConcreteProductAttributesComponent],
    exports: [EditConcreteProductAttributesComponent],
})
export class EditConcreteProductAttributesModule {}
