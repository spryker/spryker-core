import { ChangeDetectionStrategy, Component, Input, OnChanges, SimpleChanges, ViewEncapsulation } from '@angular/core';
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
export class BulkEditProductVariantsComponent implements OnChanges {
  @Input() @ToJson() sections?: BulkEditProductVariantSections;

  notificationType = NotificationType;
  statusValue = false;
  isStatusActive = false;
  validityDates: DateRangeValueInput = {};
  isValidityActive = false;
  
  ngOnChanges(changes: SimpleChanges): void {
    if ('sections' in changes) {
      this.setDefaultValues();
    }
  }

  updateStatusActivation(isActive: boolean) {
    if (isActive) {
      return;
    }

    this.setDefaultStatus();
  }

  updateValidityActivation(isActive: boolean) {
    if (isActive) {
      return;
    }

    this.setDefaultValidityDates();
  }

  private setDefaultValues() {
    this.setDefaultStatus();
    this.setDefaultValidityDates();
  }

  private setDefaultStatus() {
    this.statusValue = this.sections.status.value ?? false;
  }

  private setDefaultValidityDates() {
    this.validityDates = this.sections.validity.value ? {
      from: this.sections.validity.value.from,
      to: this.sections.validity.value.to,
    } : {};
  }
}
