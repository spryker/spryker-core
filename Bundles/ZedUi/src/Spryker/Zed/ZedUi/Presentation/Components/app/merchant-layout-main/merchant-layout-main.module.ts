import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { LayoutMainModule } from '../layout-main/layout-main.module';
import { MerchantLayoutMainComponent } from './merchant-layout-main.component';

@NgModule({
    imports: [CommonModule, LayoutMainModule],
    declarations: [MerchantLayoutMainComponent],
    exports: [MerchantLayoutMainComponent],
})
export class MerchantLayoutMainModule {}
