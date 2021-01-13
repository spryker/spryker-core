import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { ToJson } from '@spryker/utils';
import { NotificationType } from '@spryker/notification';
import { DateRangeValueInput } from '@spryker/date-picker';

import { BulkEditProductVariantSections } from './types';

@Component({
  selector: 'mp-bulk-edit-product-variants',
  templateUrl: './bulk-edit-product-variants.component.html',
  styleUrls: ['./bulk-edit-product-variants.component.less'],
  changeDetection: ChangeDetectionStrategy.OnPush,
  encapsulation: ViewEncapsulation.None,
  host: {
    class: 'mp-bulk-edit-product-variants',
  },
})
export class BulkEditProductVariantsComponent {
  @Input() @ToJson() sections?: BulkEditProductVariantSections;
  @Input() notificationText?: string;

  notificationType = NotificationType;
  statusValue = false;
  isStatusActive = false;
  validityDates: DateRangeValueInput = {};
  isValidityActive = false;

  updateStatusActivation(isActive: boolean) {
    if (isActive) {
      return;
    }

    this.statusValue = false;
  }

  updateValidityActivation(isActive: boolean) {
    if (isActive) {
      return;
    }

    this.validityDates = {};
  }
}
