import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ZedAuthFooterModule } from '../zed-auth-footer/zed-auth-footer.module';
import { ZedLayoutCentralComponent } from './zed-layout-central.component';

@NgModule({
    imports: [CommonModule, ZedAuthFooterModule],
    declarations: [ZedLayoutCentralComponent],
    exports: [ZedLayoutCentralComponent],
})
export class ZedLayoutCentralModule {
}
