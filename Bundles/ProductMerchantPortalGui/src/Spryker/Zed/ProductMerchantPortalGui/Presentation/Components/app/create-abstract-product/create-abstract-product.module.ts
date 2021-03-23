import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { CreateAbstractProductComponent } from './create-abstract-product.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [CreateAbstractProductComponent],
    exports: [CreateAbstractProductComponent],
})
export class CreateAbstractProductModule {}
