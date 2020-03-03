import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ZedAuthFooterModule } from '../zed-auth-footer/zed-auth-footer.module';
import { ZedLayoutCenteredComponent } from './zed-layout-centered.component';

@NgModule({
    imports: [CommonModule, ZedAuthFooterModule],
    declarations: [ZedLayoutCenteredComponent],
    exports: [ZedLayoutCenteredComponent],
})
export class ZedLayoutCenteredModule {
}
