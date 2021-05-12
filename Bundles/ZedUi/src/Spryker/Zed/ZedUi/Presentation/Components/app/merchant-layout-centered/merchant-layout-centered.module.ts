import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { LayoutCenteredModule } from '../layout-centered/layout-centered.module';
import { MerchantLayoutCenteredComponent } from './merchant-layout-centered.component';

@NgModule({
    imports: [CommonModule, LayoutCenteredModule],
    declarations: [MerchantLayoutCenteredComponent],
    exports: [MerchantLayoutCenteredComponent],
})
export class MerchantLayoutCenteredModule {}
