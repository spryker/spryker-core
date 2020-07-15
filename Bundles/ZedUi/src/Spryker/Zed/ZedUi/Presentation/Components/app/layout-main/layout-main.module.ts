import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { HeaderModule } from '@spryker/header';
import { LayoutModule } from '@spryker/layout';
import { LogoModule } from '@spryker/logo';
import { NavigationModule } from '@spryker/navigation';
import { SidebarModule } from '@spryker/sidebar';
import { ApplyContextsModule } from '@spryker/utils';
import { CustomElementBoundaryModule } from '@spryker/web-components';

import { IconDashboardModule, IconOffersModule, IconProfileModule } from '../../icons';
import { LayoutMainComponent } from './layout-main.component';

@NgModule({
    imports: [
        CommonModule,
        LayoutModule,
        HeaderModule,
        SidebarModule,
        LogoModule,
        NavigationModule,
        ApplyContextsModule,
        CustomElementBoundaryModule,
        IconDashboardModule,
        IconProfileModule,
        IconOffersModule,
    ],
    declarations: [LayoutMainComponent],
    exports: [LayoutMainComponent],
})
export class LayoutMainModule {}
