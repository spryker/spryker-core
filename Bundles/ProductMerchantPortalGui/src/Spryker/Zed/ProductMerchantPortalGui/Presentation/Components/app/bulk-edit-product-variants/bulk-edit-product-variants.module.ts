import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardModule } from '@spryker/card';
import { ToggleModule } from '@spryker/toggle';
import { CheckboxModule } from '@spryker/checkbox';
import { FormItemModule } from '@spryker/form-item';
import { DateRangePickerModule } from '@spryker/date-picker';
import { HeadlineModule } from '@spryker/headline';

import { BulkEditProductVariantsComponent } from './bulk-edit-product-variants.component';

@NgModule({
    imports: [
        CommonModule,
        CardModule,
        ToggleModule,
        FormItemModule,
        CheckboxModule,
        DateRangePickerModule,
        HeadlineModule,
    ],
    declarations: [BulkEditProductVariantsComponent],
    exports: [BulkEditProductVariantsComponent],
})
export class BulkEditProductVariantsModule {}
