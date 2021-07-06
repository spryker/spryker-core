import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { EditAbstractProductComponent } from './edit-abstract-product.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [EditAbstractProductComponent],
    exports: [EditAbstractProductComponent],
})
export class EditAbstractProductModule {}
