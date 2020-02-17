import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { ButtonModule, ButtonComponent } from '@spryker/button';
import { FormItemModule, FormItemComponent } from '@spryker/form-item';
import { InputModule, InputComponent } from '@spryker/input';
import { CardModule, CardComponent } from '@spryker/card';
import { LogoModule, LogoComponent } from '@spryker/logo';
import { CustomElementModule } from '@spryker/web-components';
import { ZedLayoutCentralComponent } from '../../../ZedUi/src/app/zed-layout-central/zed-layout-central.component';
import { ZedLayoutCentralModule } from '../../../ZedUi/src/app/zed-layout-central/zed-layout-central.module';

import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';

@NgModule({
    declarations: [
        AppComponent,
        LoginComponent,
    ],
    imports: [
        BrowserModule,
        ButtonModule,
        FormItemModule,
        InputModule,
        CardModule,
        LogoModule,
        ZedLayoutCentralModule,
    ],
    providers: [],
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'spy-button',
            component: ButtonComponent
        },
        {
            selector: 'mp-login',
            component: LoginComponent
        },
        {
            selector: 'spy-form-item',
            component: FormItemComponent
        },
        {
            selector: 'spy-input',
            component: InputComponent
        },
        {
            selector: 'spy-card',
            component: CardComponent
        },
        {
            selector: 'spy-logo',
            component: LogoComponent
        },
        {
            selector: 'zed-layout-central',
            component: ZedLayoutCentralComponent
        }
    ];
}
