import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LayoutModule } from '@spryker/layout';
import { HeaderModule } from '@spryker/header';
import { SidebarModule } from '@spryker/sidebar';
import { LogoModule } from '@spryker/logo';
import { NavigationModule } from '@spryker/navigation';
import { provideIcons, Icon } from '@spryker/icon';

import { LayoutMainModule } from '../layout-main/layout-main.module';
import { MerchantLayoutMainComponent } from './merchant-layout-main.component';

@NgModule({
    imports: [CommonModule, LayoutMainModule],
    declarations: [MerchantLayoutMainComponent],
    exports: [MerchantLayoutMainComponent],
})
export class MerchantLayoutMainModule {}
