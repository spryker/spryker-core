import { NgModule } from '@angular/core';
import { ButtonComponent, ButtonModule } from '@spryker/button';
import { FormItemComponent, FormItemModule } from '@spryker/form-item';
import { InputComponent, InputModule } from '@spryker/input';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';

import { LoginComponent } from './login/login.component';
import { LoginModule } from './login/login.module';

@NgModule({
    imports: [ButtonModule, FormItemModule, InputModule, LoginModule],
    providers: [],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        ButtonComponent,
        LoginComponent,
        FormItemComponent,
        InputComponent,
    ];
}
