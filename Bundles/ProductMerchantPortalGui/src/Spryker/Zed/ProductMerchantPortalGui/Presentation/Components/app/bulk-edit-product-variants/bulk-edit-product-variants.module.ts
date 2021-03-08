import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardModule } from '@spryker/card';
import { ToggleModule } from '@spryker/toggle';
import { CheckboxModule } from '@spryker/checkbox';
import { FormItemModule } from '@spryker/form-item';
import { DateRangePickerModule } from '@spryker/date-picker';

import { BulkEditProductVariantsComponent } from './bulk-edit-product-variants.component';

@NgModule({
    imports: [CommonModule, CardModule, ToggleModule, FormItemModule, CheckboxModule, DateRangePickerModule],
    declarations: [BulkEditProductVariantsComponent],
    exports: [BulkEditProductVariantsComponent],
})
export class BulkEditProductVariantsModule {}
