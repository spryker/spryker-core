import { NgModule} from '@angular/core';
import { CommonModule } from '@angular/common';
import { LayoutModule } from '@spryker/layout';
import { HeaderModule } from '@spryker/header';
import { SidebarModule } from '@spryker/sidebar';
import { LogoModule } from '@spryker/logo';
import { NavigationModule } from '@spryker/navigation';
import { ICONS_TOKEN } from '@spryker/icon';

import { ZedLayoutMainComponent } from './zed-layout-main.component';
import dashboardIcon from '../../icons/dashboard';

@NgModule({
  imports: [CommonModule, LayoutModule, HeaderModule, SidebarModule, LogoModule, NavigationModule],
  providers: [
    {
      provide: ICONS_TOKEN,
      useValue: {
        name: 'dashboard',
        svg: dashboardIcon,
      },
      multi: true,
    },
  ],
  declarations: [ZedLayoutMainComponent],
  exports: [ZedLayoutMainComponent],
})
export class ZedLayoutMainModule {
}
