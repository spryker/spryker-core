import { NgModule } from '@angular/core';
import { WebComponentsModule } from '@spryker/web-components';
import { MfaSetupTableComponent } from './mfa-setup-table/mfa-setup-table.component';
import { MfaSetupTableModule } from './mfa-setup-table/mfa-setup-table.module';
import { MfaHandlerComponent } from './mfa-handler/mfa-handler.component';
import { MfaHandlerModule } from './mfa-handler/mfa-handler.module';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([MfaSetupTableComponent, MfaHandlerComponent]),
        MfaSetupTableModule,
        MfaHandlerModule,
    ],
})
export class ComponentsModule {}
