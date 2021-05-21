import { NgModule } from '@angular/core';
import { LocaleModule, LocaleSwitcherComponent } from '@spryker/locale';
import { NotificationComponent, NotificationModule } from '@spryker/notification';
import { WebComponentsModule } from '@spryker/web-components';
import { ButtonComponent, ButtonModule } from '@spryker/button';
import { FormItemComponent, FormItemModule } from '@spryker/form-item';
import { InputComponent, InputModule } from '@spryker/input';
import { InputPasswordComponent, InputPasswordModule } from '@spryker/input.password';
import { TextareaComponent, TextareaModule } from '@spryker/textarea';
import { SelectModule, SelectComponent } from '@spryker/select';
import { ToggleModule, ToggleComponent } from '@spryker/toggle';
import { CheckboxModule, CheckboxComponent } from '@spryker/checkbox';
import { UserMenuLinkComponent, UserMenuModule } from '@spryker/user-menu';
import { LogoComponent, LogoModule } from '@spryker/logo';

import { HeaderComponent } from './header/header.component';
import { HeaderModule } from './header/header.module';
import { HeaderMenuComponent } from './header-menu/header-menu.component';
import { HeaderMenuModule } from './header-menu/header-menu.module';
import { LayoutCenteredComponent } from './layout-centered/layout-centered.component';
import { LayoutCenteredModule } from './layout-centered/layout-centered.module';
import { LayoutMainComponent } from './layout-main/layout-main.component';
import { LayoutMainModule } from './layout-main/layout-main.module';
import { MerchantLayoutCenteredComponent } from './merchant-layout-centered/merchant-layout-centered.component';
import { MerchantLayoutCenteredModule } from './merchant-layout-centered/merchant-layout-centered.module';
import { MerchantLayoutMainComponent } from './merchant-layout-main/merchant-layout-main.component';
import { MerchantLayoutMainModule } from './merchant-layout-main/merchant-layout-main.module';
import { FormComponent } from './form/form.component';
import { FormModule } from './form/form.module';
import { RootComponent } from './app.component';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            {
                component: RootComponent,
                isRoot: true,
            },
            LayoutCenteredComponent,
            LayoutMainComponent,
            MerchantLayoutCenteredComponent,
            MerchantLayoutMainComponent,
            HeaderComponent,
            HeaderMenuComponent,
            NotificationComponent,
            LocaleSwitcherComponent,
            FormComponent,
            FormItemComponent,
            InputComponent,
            InputPasswordComponent,
            ButtonComponent,
            TextareaComponent,
            SelectComponent,
            ToggleComponent,
            CheckboxComponent,
            UserMenuLinkComponent,
            LogoComponent,
        ]),
        LayoutCenteredModule,
        MerchantLayoutCenteredModule,
        MerchantLayoutMainModule,
        LayoutMainModule,
        HeaderModule,
        HeaderMenuModule,
        NotificationModule,
        LocaleModule,
        FormModule,
        FormItemModule,
        InputModule,
        InputPasswordModule,
        ButtonModule,
        TextareaModule,
        SelectModule,
        ToggleModule,
        CheckboxModule,
        UserMenuModule,
        LogoModule,
    ],
    declarations: [RootComponent],
})
export class ComponentsModule {}
