import { NgModule } from '@angular/core';
import { LocaleModule, LocaleSwitcherComponent } from '@spryker/locale';
import { NotificationComponent, NotificationModule } from '@spryker/notification';
import { CustomElementModule } from '@spryker/web-components';

import { HeaderComponent } from './header/header.component';
import { HeaderModule } from './header/header.module';
import { LayoutCenteredComponent } from './layout-centered/layout-centered.component';
import { LayoutCenteredModule } from './layout-centered/layout-centered.module';
import { LayoutMainComponent } from './layout-main/layout-main.component';
import { LayoutMainModule } from './layout-main/layout-main.module';
import { MerchantLayoutCenteredComponent } from './merchant-layout-centered/merchant-layout-centered.component';
import { MerchantLayoutCenteredModule } from './merchant-layout-centered/merchant-layout-centered.module';
import { MerchantLayoutMainComponent } from './merchant-layout-main/merchant-layout-main.component';
import { MerchantLayoutMainModule } from './merchant-layout-main/merchant-layout-main.module';

@NgModule({
    imports: [
        LayoutCenteredModule,
        MerchantLayoutCenteredModule,
        MerchantLayoutMainModule,
        LayoutMainModule,
        HeaderModule,
        NotificationModule,
        LocaleModule,
    ],
    providers: [],
})
export class ComponentsModule extends CustomElementModule {
    protected components = [
        {
            selector: 'web-mp-layout-centered',
            component: LayoutCenteredComponent,
        },
        {
            selector: 'web-mp-layout-main',
            component: LayoutMainComponent,
        },
        {
            selector: 'mp-merchant-layout-centered',
            component: MerchantLayoutCenteredComponent,
        },
        {
            selector: 'mp-merchant-layout-main',
            component: MerchantLayoutMainComponent,
        },
        {
            selector: 'mp-header',
            component: HeaderComponent,
        },
        {
            selector: 'spy-notification',
            component: NotificationComponent,
        },
        {
            selector: 'spy-locale-switcher',
            component: LocaleSwitcherComponent,
        },
    ];
}
