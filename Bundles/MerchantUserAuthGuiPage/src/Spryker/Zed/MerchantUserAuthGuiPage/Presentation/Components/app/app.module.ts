import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { ButtonComponent, ButtonModule } from '@spryker/button';
import { FormItemComponent, FormItemModule } from '@spryker/form-item';
import { InputComponent, InputModule } from '@spryker/input';
import { CustomElementModule } from '@spryker/web-components';

import { LoginComponent } from './login/login.component';
import { LoginModule } from './login/login.module';

@NgModule({
    imports: [
        BrowserModule,
        ButtonModule,
        FormItemModule,
        InputModule,
        LoginModule,
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
