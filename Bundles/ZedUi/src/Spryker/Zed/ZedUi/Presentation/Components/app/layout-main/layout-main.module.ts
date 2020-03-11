import { NgModule} from '@angular/core';
import { CommonModule } from '@angular/common';
import { LayoutModule } from '@spryker/layout';
import { HeaderModule } from '@spryker/header';
import { SidebarModule } from '@spryker/sidebar';
import { LogoModule } from '@spryker/logo';
import { NavigationModule } from '@spryker/navigation';
import { provideIcons, Icon } from '@spryker/icon';

import { LayoutMainComponent } from './layout-main.component';
import dashboardIcon from '../../icons/dashboard';

const icons: Icon[] = [
  {
    name: 'dashboard',
    svg: dashboardIcon,
  },
];

@NgModule({
  imports: [CommonModule, LayoutModule, HeaderModule, SidebarModule, LogoModule, NavigationModule],
  providers: [provideIcons(icons)],
  declarations: [LayoutMainComponent],
  exports: [LayoutMainComponent],
})
export class LayoutMainModule {
}
