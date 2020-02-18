import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { ButtonModule, ButtonComponent } from '@spryker/button';
import { FormItemModule, FormItemComponent } from '@spryker/form-item';
import { InputModule, InputComponent } from '@spryker/input';
import { CustomElementModule } from '@spryker/web-components';

import { LoginComponent } from './login/login.component';
import { LoginModule } from './login/login.module';

@NgModule({
    imports: [
        BrowserModule,
        ButtonModule,
        FormItemModule,
        InputModule,
        LoginModule
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
        }
    ];
}
