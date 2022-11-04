import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SelectModule } from '@spryker/select';
import { ButtonModule } from '@spryker/button';
import { ButtonIconModule } from '@spryker/button.icon';
import { IconModule } from '@spryker/icon';
import { InputModule } from '@spryker/input';
import { InvokeModule } from '@spryker/utils';
import { ProductAttributesSelectorComponent } from './product-attributes-selector.component';

@NgModule({
    imports: [CommonModule, SelectModule, ButtonModule, ButtonIconModule, IconModule, InputModule, InvokeModule],
    declarations: [ProductAttributesSelectorComponent],
    exports: [ProductAttributesSelectorComponent],
})
export class ProductAttributesSelectorModule {}
