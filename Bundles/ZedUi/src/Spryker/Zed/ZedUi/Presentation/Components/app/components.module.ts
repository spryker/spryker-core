import { NgModule } from '@angular/core';
import { LocaleModule, LocaleSwitcherComponent } from '@spryker/locale';
import {
    NotificationComponent,
    NotificationModule,
} from '@spryker/notification';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';

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
    protected components: WebComponentDefs = [
        {
            selector: 'mp-layout-centered',
            component: LayoutCenteredComponent,
            isRoot: true,
        },
        {
            selector: 'mp-layout-main',
            component: LayoutMainComponent,
            isRoot: true,
        },
        {
            selector: 'mp-merchant-layout-centered',
            component: MerchantLayoutCenteredComponent,
            isRoot: true,
        },
        {
            selector: 'mp-merchant-layout-main',
            component: MerchantLayoutMainComponent,
            isRoot: true,
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
