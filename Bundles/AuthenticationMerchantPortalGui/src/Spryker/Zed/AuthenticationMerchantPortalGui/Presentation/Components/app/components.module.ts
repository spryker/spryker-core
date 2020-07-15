import { NgModule } from '@angular/core';
import { ButtonComponent, ButtonModule } from '@spryker/button';
import { FormItemComponent, FormItemModule } from '@spryker/form-item';
import { InputComponent, InputModule } from '@spryker/input';
import { CustomElementModule } from '@spryker/web-components';

import { LoginComponent } from './login/login.component';
import { LoginModule } from './login/login.module';

@NgModule({
    imports: [
        ButtonModule,
        FormItemModule,
        InputModule,
        LoginModule,
    ],
    providers: [],
})
export class ComponentsModule extends CustomElementModule {
    protected components = [
        {
            selector: 'web-spy-button',
            component: ButtonComponent
        },
        {
            selector: 'web-mp-login',
            component: LoginComponent
        },
        {
            selector: 'web-spy-form-item',
            component: FormItemComponent
        },
        {
            selector: 'web-spy-input',
            component: InputComponent
        }
    ];
}
