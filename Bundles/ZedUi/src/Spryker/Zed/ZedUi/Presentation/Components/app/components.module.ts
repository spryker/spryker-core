import { NgModule } from '@angular/core';
import { ButtonComponent, ButtonModule } from '@spryker/button';
import { CheckboxComponent, CheckboxModule } from '@spryker/checkbox';
import { DatasourceDependableComponent, DatasourceDependableModule } from '@spryker/datasource.dependable';
import { FormItemComponent, FormItemModule } from '@spryker/form-item';
import { InputComponent, InputModule } from '@spryker/input';
import { InputPasswordComponent, InputPasswordModule } from '@spryker/input.password';
import { LocaleModule, LocaleSwitcherComponent } from '@spryker/locale';
import { LogoComponent, LogoModule } from '@spryker/logo';
import { NotificationComponent, NotificationModule } from '@spryker/notification';
import { SelectComponent, SelectModule } from '@spryker/select';
import { TextareaComponent, TextareaModule } from '@spryker/textarea';
import { ToggleComponent, ToggleModule } from '@spryker/toggle';
import { WebComponentsModule } from '@spryker/web-components';
import { RootComponent } from './app.component';
import { FormSubmitterComponent } from './form-submitter/form-submitter.component';
import { FormSubmitterModule } from './form-submitter/form-submitter.module';
import { FormComponent } from './form/form.component';
import { FormModule } from './form/form.module';
import { HeaderMenuComponent } from './header-menu/header-menu.component';
import { HeaderMenuModule } from './header-menu/header-menu.module';
import { LayoutCenteredComponent } from './layout-centered/layout-centered.component';
import { LayoutCenteredModule } from './layout-centered/layout-centered.module';
import { LayoutMainComponent } from './layout-main/layout-main.component';
import { LayoutMainModule } from './layout-main/layout-main.module';
import { MerchantLayoutCenteredComponent } from './merchant-layout-centered/merchant-layout-centered.component';
import { MerchantLayoutCenteredModule } from './merchant-layout-centered/merchant-layout-centered.module';
import { MerchantLayoutContentComponent } from './merchant-layout-content/merchant-layout-content.component';
import { MerchantLayoutContentModule } from './merchant-layout-content/merchant-layout-content.module';
import { MerchantLayoutMainComponent } from './merchant-layout-main/merchant-layout-main.component';
import { MerchantLayoutMainModule } from './merchant-layout-main/merchant-layout-main.module';

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
            LogoComponent,
            DatasourceDependableComponent,
            FormSubmitterComponent,
            MerchantLayoutContentComponent,
        ]),
        LayoutCenteredModule,
        MerchantLayoutCenteredModule,
        MerchantLayoutMainModule,
        LayoutMainModule,
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
        LogoModule,
        DatasourceDependableModule,
        FormSubmitterModule,
        MerchantLayoutContentModule,
    ],
    declarations: [RootComponent],
})
export class ComponentsModule {}
