import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { LayoutFooterModule } from '../layout-footer/layout-footer.module';
import { LayoutCenteredComponent } from './layout-centered.component';

@NgModule({
    imports: [CommonModule, LayoutFooterModule],
    declarations: [LayoutCenteredComponent],
    exports: [LayoutCenteredComponent],
})
export class LayoutCenteredModule {
}
