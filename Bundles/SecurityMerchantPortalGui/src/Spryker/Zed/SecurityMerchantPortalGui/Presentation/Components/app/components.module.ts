import { NgModule } from '@angular/core';
import { ButtonLinkComponent, ButtonLinkModule, ButtonModule } from '@spryker/button';
import { WebComponentsModule } from '@spryker/web-components';

import { LoginLayoutComponent } from './login-layout/login-layout.component';
import { LoginLayoutModule } from './login-layout/login-layout.module';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([ButtonLinkComponent, LoginLayoutComponent]),
        ButtonModule,
        ButtonLinkModule,
        LoginLayoutModule,
    ],
    providers: [],
})
export class ComponentsModule {}
