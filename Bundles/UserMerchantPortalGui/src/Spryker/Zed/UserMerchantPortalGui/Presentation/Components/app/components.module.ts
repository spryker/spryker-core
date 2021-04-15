import { NgModule } from '@angular/core';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';
import { ButtonActionComponent, ButtonActionModule } from '@spryker/button.action';
import { CardComponent, CardModule } from '@spryker/card';

import { MyAccountModule } from './my-account/my-account.module';
import { MyAccountComponent } from './my-account/my-account.component';
import { ChangePasswordOverlayModule } from './change-password-overlay/change-password-overlay.module';
import { ChangePasswordOverlayComponent } from './change-password-overlay/change-password-overlay.component';

@NgModule({
    imports: [MyAccountModule, ChangePasswordOverlayModule, ButtonActionModule, CardModule],
    providers: [],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        MyAccountComponent,
        ChangePasswordOverlayComponent,
        ButtonActionComponent,
        CardComponent,
    ];
}
