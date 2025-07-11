import { NgModule } from '@angular/core';
import { MfaSetupTableComponent } from './mfa-setup-table.component';
import { HeadlineModule } from '@spryker/headline';
import { CardModule } from '@spryker/card';
import { ChipsModule } from '@spryker/chips';

@NgModule({
    imports: [HeadlineModule, CardModule, ChipsModule],
    declarations: [MfaSetupTableComponent],
    exports: [MfaSetupTableComponent],
})
export class MfaSetupTableModule {}
