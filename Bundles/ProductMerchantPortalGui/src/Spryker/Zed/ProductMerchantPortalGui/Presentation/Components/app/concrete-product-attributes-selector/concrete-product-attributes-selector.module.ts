import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormItemModule } from '@spryker/form-item';
import { SelectModule } from '@spryker/select';
import { InvokeModule } from '@spryker/utils';
import { ConcreteProductAttributesSelectorComponent } from './concrete-product-attributes-selector.component';

@NgModule({
    imports: [CommonModule, FormItemModule, SelectModule, InvokeModule],
    declarations: [ConcreteProductAttributesSelectorComponent],
    exports: [ConcreteProductAttributesSelectorComponent],
})
export class ConcreteProductAttributesSelectorModule {}
