import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RadioModule } from '@spryker/radio';
import { FormItemModule } from '@spryker/form-item';
import { IconModule } from '@spryker/icon';
import { IconInfoModule } from '@spryker/icon/icons';
import { CreateAbstractProductConcretesListComponent } from './create-abstract-product-concretes-list.component';

@NgModule({
    imports: [CommonModule, RadioModule, FormItemModule, IconModule, IconInfoModule],
    declarations: [CreateAbstractProductConcretesListComponent],
    exports: [CreateAbstractProductConcretesListComponent],
})
export class CreateAbstractProductConcretesListModule {}
