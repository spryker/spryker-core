import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MpAuthFooterModule } from '../mp-auth-footer/mp-auth-footer.module';
import { MpLayoutCenteredComponent } from './mp-layout-centered.component';

@NgModule({
    imports: [CommonModule, MpAuthFooterModule],
    declarations: [MpLayoutCenteredComponent],
    exports: [MpLayoutCenteredComponent],
})
export class MpLayoutCenteredModule {
}
