import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LayoutModule } from '@spryker/layout';
import { HeaderModule } from '@spryker/header';
import { SidebarModule } from '@spryker/sidebar';
import { LogoModule } from '@spryker/logo';
import { NavigationModule } from '@spryker/navigation';

import { LayoutMainComponent } from './layout-main.component';
import {
    IconDashboardModule,
    IconProfileModule,
    IconOffersModule,
    IconOrdersModule,
} from '../../icons';

@NgModule({
    imports: [
        CommonModule,
        LayoutModule,
        HeaderModule,
        SidebarModule,
        LogoModule,
        NavigationModule,
        IconDashboardModule,
	    IconProfileModule,
        IconOrdersModule,
        IconOffersModule,
    ],
    declarations: [LayoutMainComponent],
    exports: [LayoutMainComponent],
})
export class LayoutMainModule {
}
