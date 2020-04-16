import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AuthFooterModule } from '../auth-footer/auth-footer.module';
import { LayoutCenteredComponent } from './layout-centered.component';

@NgModule({
    imports: [CommonModule, AuthFooterModule],
    declarations: [LayoutCenteredComponent],
    exports: [LayoutCenteredComponent],
})
export class LayoutCenteredModule {
}
