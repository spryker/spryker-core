import { NgModule } from '@angular/core';
import { ButtonComponent, ButtonModule, ButtonLinkComponent, ButtonLinkModule } from '@spryker/button';
import { FormItemComponent, FormItemModule } from '@spryker/form-item';
import { InputComponent, InputModule } from '@spryker/input';
import { InputPasswordComponent, InputPasswordModule } from '@spryker/input.password';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';

import { LoginLayoutComponent } from './login-layout/login-layout.component';
import { LoginLayoutModule } from './login-layout/login-layout.module';

@NgModule({
    imports: [ButtonModule, ButtonLinkModule, FormItemModule, InputModule, InputPasswordModule, LoginLayoutModule],
    providers: [],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        ButtonComponent,
        ButtonLinkComponent,
        LoginLayoutComponent,
        FormItemComponent,
        InputComponent,
        InputPasswordComponent,
    ];
}
