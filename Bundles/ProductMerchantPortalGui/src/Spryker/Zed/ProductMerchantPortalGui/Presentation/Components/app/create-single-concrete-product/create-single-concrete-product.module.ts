import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { CreateSingleConcreteProductComponent } from './create-single-concrete-product.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [CreateSingleConcreteProductComponent],
    exports: [CreateSingleConcreteProductComponent],
})
export class CreateSingleConcreteProductModule {}
