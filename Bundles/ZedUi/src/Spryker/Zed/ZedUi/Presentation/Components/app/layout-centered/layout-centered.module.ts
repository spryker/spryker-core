import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { ApplyContextsModule } from '@spryker/utils';
import { CustomElementBoundaryModule } from '@spryker/web-components';

import { LayoutFooterModule } from '../layout-footer/layout-footer.module';
import { LayoutCenteredComponent } from './layout-centered.component';

@NgModule({
    imports: [
        CommonModule,
        ApplyContextsModule,
        CustomElementBoundaryModule,
        LayoutFooterModule,
    ],
    declarations: [LayoutCenteredComponent],
    exports: [LayoutCenteredComponent],
})
export class LayoutCenteredModule {}
