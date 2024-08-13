import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { HeadlineModule } from '@spryker/headline';

import { PaymentsComponent } from './payments.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [PaymentsComponent],
    exports: [PaymentsComponent],
})
export class PaymentsModule {}
