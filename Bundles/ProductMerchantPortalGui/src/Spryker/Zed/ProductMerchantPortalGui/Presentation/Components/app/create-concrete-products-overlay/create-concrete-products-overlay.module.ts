import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';
import { CreateConcreteProductsOverlayComponent } from './create-concrete-products-overlay.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [CreateConcreteProductsOverlayComponent],
    exports: [CreateConcreteProductsOverlayComponent],
})
export class CreateConcreteProductsOverlayModule {}
