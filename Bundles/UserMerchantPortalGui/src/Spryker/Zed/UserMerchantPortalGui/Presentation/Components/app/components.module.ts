import { NgModule } from '@angular/core';
import { WebComponentsModule } from '@spryker/web-components';
import { ButtonActionComponent, ButtonActionModule } from '@spryker/button.action';
import { CardComponent, CardModule } from '@spryker/card';

import { MyAccountModule } from './my-account/my-account.module';
import { MyAccountComponent } from './my-account/my-account.component';
import { ChangePasswordOverlayModule } from './change-password-overlay/change-password-overlay.module';
import { ChangePasswordOverlayComponent } from './change-password-overlay/change-password-overlay.component';
import { ChangeFieldOverlayModule } from './change-field-overlay/change-field-overlay.module';
import { ChangeFieldOverlayComponent } from './change-field-overlay/change-field-overlay.component';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            MyAccountComponent,
            ChangePasswordOverlayComponent,
            ChangeFieldOverlayComponent,
            ButtonActionComponent,
            CardComponent,
        ]),
        MyAccountModule,
        ChangePasswordOverlayModule,
        ChangeFieldOverlayModule,
        ButtonActionModule,
        CardModule,
    ],
})
export class ComponentsModule {}
