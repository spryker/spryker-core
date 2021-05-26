import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { EditConcreteProductComponent } from './edit-concrete-product.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [EditConcreteProductComponent],
    exports: [EditConcreteProductComponent],
})
export class EditConcreteProductModule {}
