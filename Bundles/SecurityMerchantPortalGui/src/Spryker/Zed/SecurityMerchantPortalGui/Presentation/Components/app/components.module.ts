import { NgModule } from '@angular/core';
import { ButtonLinkComponent, ButtonLinkModule } from '@spryker/button';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';

import { LoginLayoutComponent } from './login-layout/login-layout.component';
import { LoginLayoutModule } from './login-layout/login-layout.module';

@NgModule({
    imports: [ButtonLinkModule, LoginLayoutModule],
    providers: [],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        ButtonLinkComponent,
        LoginLayoutComponent,
    ];
}
